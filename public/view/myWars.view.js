sap.ui.jsview("WMS.view.myWars", {
    table: new sap.m.Table(),
    pullToRefresh: new sap.m.PullToRefresh({
        refresh: function () {
            $.ajax({
                url: "php/getWars.php?auth_code=" + auth_code,
                context: this,
                cache: false,
                success: function handleSucess(response) {
                    var model = new sap.ui.model.json.JSONModel();
                    model.setJSON(response);
                    model.setSizeLimit(1000);
                    sap.ui.getCore().getModel().setProperty("/warData", model.getData());
                    this.hide();

                },
                error: function (xhr, textStatus, errorThrown) {
                    sap.m.MessageToast.show("Error: " + errorThrown);
                    this.hide();
                }
            });
        }

    }),


    tile: new sap.m.StandardTile({
        icon: "sap-icon://target-group",
        title: "My Wars",
        info: "View my Wars",
        press: function () {
            app.to(sap.ui.getCore().byId("wars"));
        },
        number: "{/warData/declaredWars} + {/warData/activeWars}",
        numberUnit: "decl. + actv.",
        type: "Monitor"
    }),
    deleteWar: function (warID) {
        $.ajax({
            url: "php/endWar.php",
            // post the id to the script which entry should be deleted
            data: {
                "warID": warID
            },
            type: "POST",
            context: this,
            // On success pull data again and display it
            success: function handleSucess() {
                this.pullToRefresh.fireRefresh();
            },
            error: function (xhr, textStatus, errorThrown) {
                sap.m.MessageToast.show("Error: " + errorThrown);
                this.pulltoRefresh.hide();
            }
        })
    },


    createContent: function () {
        this.pullToRefresh.fireRefresh();
        this.table.addColumn(new sap.m.Column({
            mergeDuplicates: true,
            header: new sap.m.Label({
                text: "WarID"
            })
        }));
        this.table.addColumn(new sap.m.Column({
            vAlign: "Middle",
            width: "64px"
        }));
// Column for the Text to be shown
        this.table.addColumn(new sap.m.Column({
            header: new sap.m.Label({
                text: "Target Group"
            })
        }));

// Column for the Text to be shown
        this.table.addColumn(new sap.m.Column({
            header: new sap.m.Label({
                text: "Status"
            })
        }));
        if (sap.ui.getCore().getModel().getData()["auth"]["auth"] >= 2) {
            this.table.addColumn(new sap.m.Column({
                vAlign: "Middle",
                hAlign: "Right",
                width: "auto",
                visible: true
            }));
        }
        else {
            this.table.addColumn(new sap.m.Column({
                vAlign: "Middle",
                hAlign: "Right",
                width: "auto",
                visible: false
            }));


        }

        this.table.bindAggregation("items", "/warData/items",
            function (sId, oContext) {
                return new sap.m.ColumnListItem({
                    cells: [

                        new sap.m.Text({
                            text: oContext.getProperty("WarID")
                        }),
                        new sap.m.Image({
                            src: getImageURI(oContext.getProperty("TargetGroupType"), oContext.getProperty("TargetGroupID")),
                            width: "32px",
                            height: "32px"
                        }),
                        new sap.m.Text({
                            text: oContext.getProperty("TargetGroupName")
                        }),
                        new sap.m.Text({
                            text: text_status(oContext.getProperty("status"))
                        }),


                        new sap.ui.core.Icon({
                            src: "sap-icon://delete",
                            color: "red",
                            press: function () {
                                jQuery.sap.require("sap.m.MessageBox");
                                sap.m.MessageBox.confirm(
                                    "Do you really want to remove the war against " + oContext.getProperty("TargetGroupName") + "?", {
                                        icon: sap.m.MessageBox.Icon.WARNING,
                                        title: "Warning",
                                        actions: [sap.m.MessageBox.Action.YES, sap.m.MessageBox.Action.NO],
                                        onClose: function (oAction) {
                                            if (oAction == "YES") {
                                                sap.ui.getCore().byId("wars").deleteWar(oContext
                                                    .getProperty("WarID"));
                                            }
                                        }
                                    });
                            }
                        })


                    ]
                })
            },
            new sap.ui.model.Sorter("WarID", true));
        return new sap.m.Page({
            title: "My Wars",
            showNavButton: true,
            navButtonPress: function () {
                app.to("view.launchpad");
            },
            content: [this.pullToRefresh, this.table]
        });
    }
});
function getImageURI(type, id) {
    var uri = "https://image.eveonline.com/";

    if (type == 32) {
        uri += "Alliance/";
    }
    else {
        uri += "Corporation/";
    }
    uri += id;
    uri += "_128.png";
    return uri;
}

function text_status(status) {
    if (status == 1) {
        return "Active";
    }
    else if (status == 0) {
        return "Declared";
    }
}