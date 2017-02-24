sap.ui.jsview("WMS.view.myGroup", {
        table: new sap.m.Table(),
        pullToRefresh: new sap.m.PullToRefresh({
            refresh: function () {
                $.ajax({
                    url: "php/getChars.php?auth_code=" + auth_code,
                    context: this,
                    cache: false,
                    // On success add the response to the table as model
                    success: function handleSucess(response) {
                        var model = new sap.ui.model.json.JSONModel();
                        model.setJSON(response);
                        sap.ui.getCore().getModel().setProperty("/charsData", model.getData());
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
                url: "php/deleteChar.php",
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
            });
        },
        tile: new sap.m.StandardTile({
            icon: "sap-icon://manager",
            //    icon: "https://image.eveonline.com/Character/92439100_32.jpg",
            title: "My Group",
            info: "Manage my Group",
            press: function () {
                app.to(sap.ui.getCore().byId("group"));
            },
            number: "{/charsData/mainchars} + {/charsData/altchars}",
            numberUnit: "Mains + Alts",

            type: "Monitor"
        })
        ,
        createContent: function () {
            this.pullToRefresh.fireRefresh();
            this.table.addColumn(new sap.m.Column({
                mergeDuplicates: true,
                mergeFunctionName: "getSrc",

                width: "64px"
            }));
            this.table.addColumn(new sap.m.Column({
                mergeDuplicates: true,
                header: new sap.m.Label({
                    text: "Mainchar"
                })
            }));
// Column for the Text to be shown
            this.table.addColumn(new sap.m.Column({
                mergeDuplicates: true,
                width: "64px"
            }));
            this.table.addColumn(new sap.m.Column({
                header: new sap.m.Label({
                    text: "Altchar"
                })
            }));
// Column for the Text to be shown
            this.table.addColumn(new sap.m.Column({
                header: new sap.m.Label({
                    text: "added"
                })
            }));
// Column for the delete Icon to be shown
            this.table.addColumn(new sap.m.Column({
                hAlign: "Right",
                width: "auto"
            }));
// bins the data from /nwsData to the table
            this.table.bindAggregation("items", "/charsData/items",
                function (sId, oContext) {
                    return new sap.m.ColumnListItem({
                            cells: [
                                new sap.m.Image({
                                    src: "https://image.eveonline.com/Character/" + oContext.getProperty("mainCharID") + "_128.jpg",
                                    width: "32px",
                                    height: "32px"
                                }),
                                // duser displayed as text
                                new sap.m.Text({
                                    text: oContext.getProperty("mainCharName")
                                }),
                                // first_name displayed as text
                                new sap.m.Image({
                                    src: "https://image.eveonline.com/Character/" + oContext.getProperty("characterID") + "_128.jpg",
                                    width: "32px",
                                    height: "32px"
                                }),
                                new sap.m.Text({
                                    text: oContext.getProperty("characterName")
                                }),
                                new sap.m.Text({
                                    text: oContext.getProperty("changed")
                                }),
                                // Icon displayed as Icon
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
                                                        console.log(this);
                                                        sap.ui.getCore().byId("group").deleteChar(oContext
                                                            .getProperty("characterID"), auth_code);
                                                    }
                                                }
                                            });


                                    }
                                })]
                        }
                    )
                },
                [new sap.ui.model.Sorter("mainCharName", false),
                    new sap.ui.model.Sorter("characterName", false)]);

            return new sap.m.Page({
                title: "My Group",
                showNavButton: true,
                navButtonPress: function () {
                    app.to("view.launchpad");
                },
                content: [
                    this.pullToRefresh,
                    this.table
                ]
            });

        }
    }
);