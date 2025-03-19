@extends('admin.layouts.main')
@section('title')
How To PlaFy
@endsection
@section('container')

<div class="main-content">	<script type="text/javascript" src="adminassets/tinymce/tinymce.min.js"></script>
<script type="text/javascript">var baseurl="https://realratanmatka.org/";
tinymce.init({    mode : "specific_textareas",
				  editor_selector : "textarea1",
				  theme: "modern",
				  branding: false,
				  table_default_attributes: {		'class': 'table'	},
				  table_default_styles: {		'border-collapsed': 'collapse',		'width': '100%'	},
				  table_responsive_width: true,    paste_data_images: true,    plugins: [      "advlist autolink lists link image charmap print preview hr anchor pagebreak",      "searchreplace wordcount visualblocks visualchars code fullscreen",      "insertdatetime media nonbreaking save table contextmenu directionality",      "emoticons template paste textcolor colorpicker textpattern"    ],    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",    toolbar2: "print preview media | forecolor backcolor emoticons",    /* image_advtab: true,     file_picker_callback: function(callback, value, meta) {      if (meta.filetype == 'image') {        $('#upload').trigger('click');        $('#upload').on('change', function() {          var file = this.files[0];          var reader = new FileReader();          reader.onload = function(e) {            callback(e.target.result, {              alt: ''            });          };          reader.readAsDataURL(file);        });      }    }, */	images_upload_url: baseurl+'tinymce-upload-image',        images_upload_handler: function (blobInfo, success, failure) {        var xhr, formData;              xhr = new XMLHttpRequest();        xhr.withCredentials = false;        xhr.open('POST', baseurl+'tinymce-upload-image');              xhr.onload = function() {            var json;                    if (xhr.status != 200) {                failure('HTTP Error: ' + xhr.status);                return;            }                    json = JSON.parse(xhr.responseText);                    if (!json || typeof json.location != 'string') {                failure('Invalid JSON: ' + xhr.responseText);                return;            }                    success(json.location);        };              formData = new FormData();        formData.append('file', blobInfo.blob(), blobInfo.filename());              xhr.send(formData);    },    templates: [{      title: 'Test template 1',      content: 'Test 1'    }, {      title: 'Test template 2',      content: 'Test 2'    }]  });</script>

<div class="page-content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12 col-md-8 mr-auto ml-auto">
				<div class="row">
					<div class="col-sm-12">
						<div class="card">

							<div class="card-body">
								<h4 class="card-title">How To Play</h4>
								<form class="theme-form mega-form" id="howToPlayFrm" name="howToPlayFrm" method="post">
									<input type="hidden" name="id" value="1">
									<div class="form-group">
										<label>How To Play Content</label>
										<textarea class="form-control textarea1" name="description" rows="10" id="description"><p>Simply download our application from Google Play Store or from our official website.&nbsp;</p>
<p>Register with your Mobile Number, Email ID, User Name with our platform.&nbsp;</p>
<p>Login with the application using Mobile Number and Password with your secure PIN code.&nbsp;</p>
<p>Select the Game type, select your favourite number and start to Play Game.&nbsp;</p>
<p>Get a chance to win upto 10 Lac Points.&nbsp;</p></textarea>
									</div>
									<div class="form-group">
										<label for="exampleInputEmail1">Video Link</label>
										<input type="text" name="video_link" id="video_link" class="form-control" placeholder="Enter Video Link" value="https://www.instagram.com/reel/C1fHNOhS7jn/?igsh=MXZ0eXV0czl3czZodw=="/>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary waves-light m-t-10" id="submitBtn">Submit</button>
									</div>
									<div class="form-group">
										<div id="error"></div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
