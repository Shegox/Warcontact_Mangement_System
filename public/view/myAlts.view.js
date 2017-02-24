sap.ui.jsview("WMS.view.myAlts", {
        table: new sap.m.Table(),
        pullToRefresh: new sap.m.PullToRefresh({
            refresh: function () {
                $.ajax({
                    url: "php/getMyChars.php?auth_code=" + auth_code,
                    context: this,
                    cache: false,
                    success: function handleSucess(response) {
                        var model = new sap.ui.model.json.JSONModel();
                        model.setJSON(response);
                        sap.ui.getCore().getModel().setProperty("/myCharsData", model.getData());
                        this.hide();
                    },
                    error: function (xhr, textStatus, errorThrown) {
                        sap.m.MessageToast.show("Error: " + errorThrown);
                        this.hide();

                    }
                });

            }

        }),
        deleteChar: function (characterID, auth_code) {
            $.ajax({
                url: "php/deleteMyChar.php",
                // post the id to the script which entry should be deleted
                data: {
                    "characterID": characterID,
                    "auth_code": auth_code
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
        tile: new sap.m.StandardTile({
            icon: "sap-icon://my-view",
            title: "My Alts",
            info: "Manage my Altchars",
            press: function () {
                app.to(sap.ui.getCore().byId("alts"));
            },
            number: "{/myCharsData/myAlts}",
            numberUnit: "Chars",

            type: "Monitor"
        })
        ,
        createContent: function () {
            this.pullToRefresh.fireRefresh();
            this.table.addColumn(new sap.m.Column({
                vAlign: "Middle",
                width: "64px"
            }));


            this.table.addColumn(new sap.m.Column({
                vAlign: "Middle",
                header: new sap.m.Label({
                    text: "Altchar"
                })
            }));
            this.table.addColumn(new sap.m.Column({
                vAlign: "Middle",
                header: new sap.m.Label({
                    text: "added"
                })
            }));
            this.table.addColumn(new sap.m.Column({
                vAlign: "Middle",
                hAlign: "Right",
                width: "auto"
            }));
            this.table.bindAggregation("items", "/myCharsData/items",
                function (sId, oContext) {
                    return new sap.m.ColumnListItem({
                        cells: [
                            new sap.m.Image({
                                src: "https://image.eveonline.com/Character/" + oContext.getProperty("characterID") + "_128.jpg",
                                width: "64px",
                                height: "64px"

                            }),
                            new sap.m.Text({
                                text: oContext.getProperty("characterName")
                            }),
                            new sap.m.Text({
                                text: oContext.getProperty("changed")
                            }),
                            new sap.ui.core.Icon({
                                src: "sap-icon://delete",
                                color: "red",
                                press: function () {
                                    jQuery.sap.require("sap.m.MessageBox");
                                    sap.m.MessageBox.confirm(
                                        "Do you really want to remove the char " + oContext.getProperty("characterName") + "?", {
                                            icon: sap.m.MessageBox.Icon.WARNING,
                                            title: "Warning",
                                            actions: [sap.m.MessageBox.Action.YES, sap.m.MessageBox.Action.NO],
                                            onClose: function (oAction) {
                                                if (oAction == "YES") {
                                                    sap.ui.getCore().byId("alts").deleteChar(oContext
                                                        .getProperty("characterID"), auth_code);
                                                }
                                            }
                                        });
                                }
                            })]
                    })
                },
                new sap.ui.model.Sorter("characterName", false));
            return new sap.m.Page({
                title: "My Alts",
                showNavButton: true,
                navButtonPress: function () {
                    app.to("view.launchpad");
                },
                content: [this.pullToRefresh, this.table],
                headerContent: []

            });

        }
    }
);