sap.ui.jsview("WMS.view.launchpad", {
        addCharTile: new sap.m.StandardTile({
            title: "Add Char",
            info: "Add new char",
            press: function () {
                window.open("php/addChar.php?state=" + auth_code, "_self");
            },
            type: "Create"
        }),

        createContent: function () {
            return new sap.m.Page("launchpad", {
                title: "Warcontact Management System",
                headerContent: [new sap.m.Button({
                    icon: "sap-icon://log",
                    press: function () {
                        window.open("php/sessionClear.php", "_self");
                    }
                })],
                content: []
            });
        }
    }
);