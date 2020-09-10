// Register a plugin named "multipic".
(function()
{
    CKEDITOR.plugins.add( 'multipic',
    {
        init : function( editor )
        {
            // Register the command.
            editor.addCommand( 'multipic',{
                exec : function( editor )
                {
                    // Create the element that represents a print break.
                    // alert('dedepageCmd!');
					var mpic = document.getElementById("mPic");
					if(mpic != null && typeof mpic != 'undefined' )
					{
						if(mpic.style.display=='none')
						{
							mpic.style.display='block';
						} else {
							mpic.style.display='none';
						}
					}else {
						alert('批量上传图片上传区域出错!');
					}
                }
            });
            // alert('dedepage!');
            // Register the toolbar button.
            editor.ui.addButton( 'MultiPic',
            {
                label : '批量上传图片',
                command : 'multipic',
                icon: 'images/multipic.gif'
            });
            // alert(editor.name);
        },
        requires : [ 'fakeobjects' ]
    });
})();
