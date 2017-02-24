sap.ui.jsview("WMS.view.logIn", {
    createContent: function () {
        return new sap.m.Page({
            title: "Warcontact Management System",
            content: [
                new sap.m.TileContainer({
                    tiles: [
                        new sap.m.CustomTile({
                            width: "auto",
                            content: new sap.m.Image({
                                src: "Login_Button.png",
                                width: "inherit"
                            }),
                            press: function () {
                                window.open("php/addChar.php?state=logIn", "_self");
                            }
                        })
                    ]
                })
            ]

        });
    }
});