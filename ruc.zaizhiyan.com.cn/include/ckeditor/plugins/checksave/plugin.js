// Register a plugin named "checksave".
(function()
{
    CKEDITOR.plugins.add( 'checksave',
    {
        init : function( editor )
        {
            // Register the command.
            editor.addCommand( 'checksave',{
                exec : function( editor )
                {
var $form = editor.element.$.form;

if ( $form )
{
	if($form.title.value=='')
	{
		alert('文章标题不能为空！');
	}
	else if($form.typeid.value==0)
	{
		alert('请选择档案的主类别！');
	}else{
		try
		{
			$form.submit();
		}
		catch( e )
		{
			// If there's a button named "submit" then the form.submit
			// function is masked and can't be called in IE/FF, so we
			// call the click() method of that button.
			if ( $form.submit.click )
				$form.submit.click();
		}
	}
}
                }
            });
            // Register the toolbar button.
            editor.ui.addButton( 'Save',
            {
                label : '保存内容',
                command : 'checksave',
                icon: 'images/save.gif'
            });
            // alert(editor.name);
        },
        requires : [ 'fakeobjects' ]
    });
})();
