    var addField = new sap.m.Input({
                id:"addField",
                placeholder: "Enter charactername to add",
                Width: "20%",
                startSuggestion: 3,
                showSuggestion: true,
                valueLiveUpdate: true,
                change: function () {
                    sap.ui.getCore().byId("hostilealts").addChar(
                        sap.ui.getCore().byId("addField").getProperty("selectedKey"), 
                        sap.ui.getCore().byId("addField").getValue(), 
                        auth_code);
                },
                suggest: function(oEvent){
				var sValue = oEvent.getParameter("suggestValue");
				
				$.ajax({
					url: "https://esi.tech.ccp.is/latest/search/?search="+sValue+"&categories=character&language=en-us&strict=false&datasource=tranquility",					
					success: function handleSucess(aData) {
						if(sValue == addField.getValue()){ //Only do something if the value wasn't changed in the meantime
						console.log(aData);
						$.ajax({
							url: "https://esi.tech.ccp.is/latest/characters/names/?character_ids="+aData.character.join()+"&datasource=tranquility",					
							success: function handleSucess(bData) {
								if(sValue ==  addField.getValue()){ //Only do something if the value wasn't changed in the meantime
								fcs = bData;
								console.log(bData);
								addField.destroySuggestionItems();
									for(var i=0; i<bData.length; i++){
										addField.addSuggestionItem(new sap.ui.core.ListItem({icon: "https://image.eveonline.com/Character/"+bData[i].character_id+"_64.jpg",text: bData[i].character_name, key: bData[i].character_id  }));
									}
								}
							},
							error: function (xhr, textStatus, errorThrown) {
							}
						});
					}},
					error: function (xhr, textStatus, errorThrown) {
					}
            })
                },
         })
sap.ui.jsview("WMS.view.hostilealts", {
    
      
    barHead: new sap.m.Bar({
    contentLeft: this.addField
            //new sap.m.Label({text: "Add hostile alt:"},
            
    }),
    table: new sap.m.Table(),
	pullToRefresh: new sap.m.PullToRefresh({
refresh: function()
{
 $.ajax({
                url: "php/getHostileAltChars.php?auth_code=" + auth_code,
                context: this,
                cache: false,
                success: function handleSucess(response) {
                    var model = new sap.ui.model.json.JSONModel();
                    model.setJSON(response);                   
		            sap.ui.getCore().getModel().setProperty("/hostileCharsData",model.getData());
                    this.hide();
                },
                error: function (xhr, textStatus, errorThrown) {
				sap.m.MessageToast.show("Error: "+ errorThrown);
                this.hide();

                }
            });

}
	
}),
          deleteChar: function (characterID, auth_code) {
            $.ajax({
                url: "php/deleteHostileAltChar.php",
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
				sap.m.MessageToast.show("Error: "+ errorThrown);
			    this.pulltoRefresh.hide();
                }
            })
        },
         
            handleSuggest: function(oEvent) {
            var sTerm = oEvent.getParameter("suggestValue");
            var aFilters = [];
            if (sTerm) {
                aFilters.push(new Filter("Name", sap.ui.model.FilterOperator.StartsWith, sTerm));
            }
            oEvent.getSource().getBinding("suggestionItems").filter(aFilters);
            },


         
         addChar: function (characterID, characterName, auth_code) {
            $.ajax({
                url: "php/addHostileAltChar.php",
                // post the id to the script which entry should be deleted
                data: {
                    "characterID": characterID,
                    "characterName": characterName,
                    "auth_code": auth_code
                },
                type: "POST",
                context: this,
                // On success pull data again and display it
                success: function handleSucess() {
                sap.ui.getCore().byId("addField").setValue("");   
			    this.pullToRefresh.fireRefresh();
                },
                error: function (xhr, textStatus, errorThrown) {
				sap.m.MessageToast.show("Error: "+ errorThrown);
			    //this.pulltoRefresh.hide();
                }
            })
        },


        updateAltCharTag: function (characterID, tag, auth_code) {
            $.ajax({
                url: "php/updateAltCharTag.php",
                // post the id to the script which entry should be deleted
                data: {
                    "characterID": characterID,
                    "tag": tag,
                    "auth_code": auth_code
                },
                type: "POST",
                context: this,
                // On success pull data again and display it
                success: function handleSucess() {
        	    this.pullToRefresh.fireRefresh();
                },
                error: function (xhr, textStatus, errorThrown) {
				sap.m.MessageToast.show("Error: "+ errorThrown);
			    //this.pulltoRefresh.hide();
                }
            })
        },


        

        tile:new sap.m.StandardTile({
	    icon: "sap-icon://tags",
            title: "Hostile Alts",
            info: "Manage hostile Altchars",            
            press: function () {
               app.to(sap.ui.getCore().byId("hostilealts"));
            },
            number: "{/hostileCharsData/charCount}",
	    numberUnit: "Chars",
		
		type:"Monitor",
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
        width: "10%",
        header: new sap.m.Label({
        text: "Altchar"
        })
            }));
        this.table.addColumn(new sap.m.Column({
		vAlign: "Middle",
        hAlign:"Left",
        width: "10%",
        header: new sap.m.Label({
        text: "Tag"
                })
            }));
            this.table.addColumn(new sap.m.Column({
		vAlign: "Middle",
                header: new sap.m.Label({
                    text: "added by"
                })
            }));
            this.table.addColumn(new sap.m.Column({
		vAlign: "Middle",
                header: new sap.m.Label({
                    text: "date added"
                })
            }));
            this.table.addColumn(new sap.m.Column({
		vAlign: "Middle",
                hAlign: "Right",
                width: "auto"
            }));
            this.table.bindAggregation("items", "/hostileCharsData/items",
                function (sId, oContext) {
                    return new sap.m.ColumnListItem({
                        cells: [
			    new sap.m.Image({
				src: "https://image.eveonline.com/Character/"+oContext.getProperty("characterID")+"_64.jpg",
				width: "64px",
				height: "64px",

			    }),
                            new sap.m.Text({
                                text: oContext.getProperty("characterName")
                            }),
                            new sap.m.Input({
                                value: oContext.getProperty("tag"),
                                maxLength: 20,
                                placeholder: "Enter tag",
                                change: function(oData) {
                                    sap.ui.getCore().byId("hostilealts").updateAltCharTag(
                                        oContext.getProperty("characterID"),
                                        oData.getParameter("value"), 
                                        auth_code)
                               }
                               
                            }),
                            new sap.m.Text({
                                text: oContext.getProperty("mainCharName")
                            }),
                            new sap.m.Text({
                                text: oContext.getProperty("added")
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
                                                    sap.ui.getCore().byId("hostilealts").deleteChar(oContext
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
                title: "Hostile Alts",
                showNavButton: true,
                navButtonPress: function () {
                    app.to("view.launchpad");
                },
                content: [this.addField,this.barHead,this.pullToRefresh, this.table],
                headerContent: []

            });

        }
    }
);