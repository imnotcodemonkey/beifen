/**
 * @file �����������
 */

(function()
{
	var addonDialog = function( editor, dialogType )
	{
		return {
			title : '��������',
			minWidth : 420,
			minHeight : 160,
			onOk : function()
			{
				var addonUrl = this.getValueOf( 'Link', 'txtUrl' );
				var addonTitle = this.getValueOf( 'Link', 'txtTitle');
				var tempvar='<table width="90%" border="0" cellspacing="0" cellpadding="0">\r    <tbody>\r        <tr>\r            <td width="130"><a target="_blank" href="'+addonUrl+'"><img border="0" align="middle" src="/res/lhbico/downnow.png" alt="��������" /></a></td>\r            <td><a target="_blank" href="'+addonUrl+'"><u>'+addonTitle+'</u></a></td>\r        </tr>\r    </tbody>\r</table>';
				editor.insertHtml(tempvar);
				
			},
			contents : [
				{
					id : 'Link',
					label : '����',
					padding : 0,
					type : 'vbox',
					elements :
					[
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									id : 'txtTitle',
									type : 'text',
									label : '��������',
									style : 'width: 60%',
									'default' : ''
								},
								{
									id : 'txtUrl',
									type : 'text',
									label : 'ѡ�񸽼�',
									style : 'width: 100%',
									'default' : ''
								},
								{
									type : 'button',
									id : 'browse',
									filebrowser :
									{
										action : 'Browse',
										target: 'Link:txtUrl',
										url: '../include/dialog/browse_soft.php'
									},
									style : 'float:right',
									hidden : true,
									label : editor.lang.common.browseServer
								}
							]
						}
					]
				}
			]
		};
	};

	CKEDITOR.dialog.add( 'addon', function( editor )
		{
			return addonDialog( editor, 'addon' );
		});
})();
