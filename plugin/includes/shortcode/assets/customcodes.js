(function() {
    tinymce.create('tinymce.plugins.Speed', {
        init : function(ed, url) {
            ed.addButton('nota-restrita', {
                title : 'O conteúdo só estará disponível para usuários logados',
                image : url+'/nota-restrita.png',
                onclick : function() {
                     ed.selection.setContent('[nota-restrita]' + ed.selection.getContent() + '[/nota-restrita]');

                }
            });

            ed.addButton('embed', {
                title : 'Embedar URL',
                image : url+'/embed.png',
                onclick : function() {
                     ed.selection.setContent('[embed]' + ed.selection.getContent() + '[/embed]');

                }
            });

            ed.addButton('label-default', {
                title : 'Label Padrão',
                image : url+'/label-default.png',
                onclick : function() {
                     ed.selection.setContent('[label]' + ed.selection.getContent() + '[/label]');

                }
            });

            ed.addButton('label-success', {
                title : 'Label Sucesso',
                image : url+'/label-success.png',
                onclick : function() {
                     ed.selection.setContent('[label-success]' + ed.selection.getContent() + '[/label-success]');

                }
            });

            ed.addButton('label-warning', {
                title : 'Label Alerta',
                image : url+'/label-warning.png',
                onclick : function() {
                     ed.selection.setContent('[label-warning]' + ed.selection.getContent() + '[/label-warning]');

                }
            });

            ed.addButton('label-danger', {
                title : 'Label Perigo',
                image : url+'/label-danger.png',
                onclick : function() {
                     ed.selection.setContent('[label-danger]' + ed.selection.getContent() + '[/label-danger]');

                }
            });

            ed.addButton('label-info', {
                title : 'Label Informação',
                image : url+'/label-info.png',
                onclick : function() {
                     ed.selection.setContent('[label-info]' + ed.selection.getContent() + '[/label-info]');

                }
            });

        },
        createControl : function(n, cm) {
            return null;
        },
    });
    tinymce.PluginManager.add('speed', tinymce.plugins.Speed);
})();