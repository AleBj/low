tinymce.init({
    selector: "textarea",
    theme: "modern",	
   /* plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table contextmenu directionality",
        "emoticons template paste textcolor moxiemanager"
    ],*/
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ]
});

/*tinymce.init({
	selector: "input#titulo",
   	forced_root_block : '',
   force_br_newlines : false,
   force_p_newlines : false
})
	
	
tinymce.init({
	selector: "input#titulo_form",
	forced_root_block : '',
   force_br_newlines : false,
   force_p_newlines : false
})*/