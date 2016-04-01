(function() {

    // ---------------------- BEGIN ROW

    tinymce.create('tinymce.plugins.beginrw', {
        init : function(ed, url) {
            ed.addButton('beginrw', {
                title : 'Row - begin',
                image : url+'/images/begin.png',
                onclick : function() {
                	 ed.selection.setContent('[beginrw /]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('beginrw', tinymce.plugins.beginrw);

    // ---------------------- end BEGIN ROW

    // ---------------------- ONE COLUMN

    tinymce.create('tinymce.plugins.onecl', {
        init : function(ed, url) {
            ed.addButton('onecl', {
                title : 'One column',
                image : url+'/images/one.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[onecl]' + $text + '[/onecl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('onecl', tinymce.plugins.onecl);

    // ---------------------- end ONE COLUMN
    
    // ---------------------- TWO COLUMNS

    tinymce.create('tinymce.plugins.twocl', {
        init : function(ed, url) {
            ed.addButton('twocl', {
                title : 'Two columns',
                image : url+'/images/two.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[twocl]' + $text + '[/twocl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('twocl', tinymce.plugins.twocl);

    // ---------------------- end TWO COLUMNS
    
    // ---------------------- THREE COLUMNS

    tinymce.create('tinymce.plugins.threecl', {
        init : function(ed, url) {
            ed.addButton('threecl', {
                title : 'Three columns',
                image : url+'/images/three.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[threecl]' + $text + '[/threecl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('threecl', tinymce.plugins.threecl);

    // ---------------------- end THREE COLUMNS
    
    // ---------------------- FOUR COLUMNS

    tinymce.create('tinymce.plugins.fourcl', {
        init : function(ed, url) {
            ed.addButton('fourcl', {
                title : 'Four columns',
                image : url+'/images/four.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[fourcl]' + $text + '[/fourcl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('fourcl', tinymce.plugins.fourcl);

    // ---------------------- end FOUR COLUMNS
    
    // ---------------------- FIVE COLUMNS

    tinymce.create('tinymce.plugins.fivecl', {
        init : function(ed, url) {
            ed.addButton('fivecl', {
                title : 'Five columns',
                image : url+'/images/five.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[fivecl]' + $text + '[/fivecl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('fivecl', tinymce.plugins.fivecl);

    // ---------------------- end FIVE COLUMNS
    
    // ---------------------- SIX COLUMNS

    tinymce.create('tinymce.plugins.sixcl', {
        init : function(ed, url) {
            ed.addButton('sixcl', {
                title : 'Six columns',
                image : url+'/images/six.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[sixcl]' + $text + '[/sixcl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('sixcl', tinymce.plugins.sixcl);

    // ---------------------- end SIX COLUMNS
    
    // ---------------------- SEVEN COLUMNS

    tinymce.create('tinymce.plugins.sevencl', {
        init : function(ed, url) {
            ed.addButton('sevencl', {
                title : 'Seven columns',
                image : url+'/images/seven.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[sevencl]' + $text + '[/sevencl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('sevencl', tinymce.plugins.sevencl);

    // ---------------------- end SEVEN COLUMNS
    
     // ---------------------- EIGHT COLUMNS

    tinymce.create('tinymce.plugins.eightcl', {
        init : function(ed, url) {
            ed.addButton('eightcl', {
                title : 'Eight columns',
                image : url+'/images/eight.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[eightcl]' + $text + '[/eightcl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('eightcl', tinymce.plugins.eightcl);

    // ---------------------- end EIGHT COLUMNS
    
     // ---------------------- NINE COLUMNS

    tinymce.create('tinymce.plugins.ninecl', {
        init : function(ed, url) {
            ed.addButton('ninecl', {
                title : 'Nine columns',
                image : url+'/images/nine.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[ninecl]' + $text + '[/ninecl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('ninecl', tinymce.plugins.ninecl);

    // ---------------------- end NINE COLUMNS
    
     // ---------------------- TEN COLUMNS

    tinymce.create('tinymce.plugins.tencl', {
        init : function(ed, url) {
            ed.addButton('tencl', {
                title : 'Ten columns',
                image : url+'/images/ten.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[tencl]' + $text + '[/tencl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('tencl', tinymce.plugins.tencl);

    // ---------------------- end TEN COLUMNS
    
    // ---------------------- ELEVEN COLUMNS

    tinymce.create('tinymce.plugins.elevencl', {
        init : function(ed, url) {
            ed.addButton('elevencl', {
                title : 'Eleven columns',
                image : url+'/images/eleven.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[elevencl]' + $text + '[/elevencl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('elevencl', tinymce.plugins.elevencl);

    // ---------------------- end ELEVEN COLUMNS
    
    // ---------------------- TWELVE COLUMNS

    tinymce.create('tinymce.plugins.twelvecl', {
        init : function(ed, url) {
            ed.addButton('twelvecl', {
                title : 'Twelve columns',
                image : url+'/images/twelve.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[twelvecl]' + $text + '[/twelvecl]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('twelvecl', tinymce.plugins.twelvecl);

    // ---------------------- end TWELVE COLUMNS
    
    // ---------------------- END

    tinymce.create('tinymce.plugins.endrw', {
        init : function(ed, url) {
            ed.addButton('endrw', {
                title : 'Row - end',
                image : url+'/images/end.png',
                onclick : function() {
                	 ed.selection.setContent('[endrw /]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('endrw', tinymce.plugins.endrw);

    // ---------------------- end END
    
    // ---------------------- TABS

    tinymce.create('tinymce.plugins.tabs', {
        init : function(ed, url) {
            ed.addButton('tabs', {
                title : 'Tabs',
                image : url+'/images/tabs.png',
                onclick : function() {
                	 ed.selection.setContent('[tabgroup]<br />[tab state="active" title="Tab1"]Content of Tab 1[/tab]<br />[tab title="Tab2"]Content of Tab 2[/tab]<br />[/tabgroup]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('tabs', tinymce.plugins.tabs);

    // ---------------------- end TABS
    
    // ---------------------- ACCORDION

    tinymce.create('tinymce.plugins.accordion', {
        init : function(ed, url) {
            ed.addButton('accordion', {
                title : 'Accordion',
                image : url+'/images/accordion.png',
                onclick : function() {
                	 ed.selection.setContent('[accordiongroup id="id-accordion"]<br />[accordion id="accordion1" title="Toggle Accordion 1" state="active"] accordion content #1 [/accordion]<br />[accordion id="accordion2" title="Toggle Accordion 1"] accordion content #2 [/accordion]<br />[accordion id="accordion3" title="Toggle Accordion 3"] accordion content #3 [/accordion]<br />[/accordiongroup]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('accordion', tinymce.plugins.accordion);

    // ---------------------- end ACCORDION

	// ---------------------- INFOBOX

    tinymce.create('tinymce.plugins.infobox', {
        init : function(ed, url) {
            ed.addButton('infobox', {
                title : 'Infobox',
                image : url+'/images/infobox.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[infobox]' + $text + '[/infobox]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('infobox', tinymce.plugins.infobox);

    // ---------------------- end INFOBOX
    
    // ---------------------- EVIDENCE

    tinymce.create('tinymce.plugins.evidence', {
        init : function(ed, url) {
            ed.addButton('evidence', {
                title : 'Evidence',
                image : url+'/images/evidence.png',
                onclick : function() {
                	 var $text = (ed.selection.getContent() != "") ? ed.selection.getContent() : " Here goes your content ";
                     ed.selection.setContent('[evidence]' + $text + '[/evidence]');
                     ed.undoManager.add();
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('evidence', tinymce.plugins.evidence);

    // ---------------------- end EVIDENCE
    
    

})();