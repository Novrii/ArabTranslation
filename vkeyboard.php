<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title></title>
    <link href="arabickeyboard/css/keyboard.css" rel="stylesheet">
    <script src="arabickeyboard/js/jquery.keyboard.js"></script>

    <!-- keyboard extensions (optional) -->
    <script src="arabickeyboard/js/jquery.mousewheel.js"></script>
    <script src="arabickeyboard/js/jquery.keyboard.extension-typing.js"></script>

    <!-- preloaded keyboard layout -->
    <!-- <script src="arabickeyboard/layouts/keyboard-layouts-greywyvern.js" charset="utf-8"></script> -->

    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/ui-lightness/jquery-ui.css"></script>
    <script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

    <script>
      $(function() {

      $('#keyboard').keyboard({

        usePreview: false,

        visible: function(e, keyboard, el) {
          keyboard.$el.addClass('red');
        },
        beforeClose: function(e, keyboard, el, accepted) {
          keyboard.$el.removeClass('red');
        }
      })

      });

    </script>
    <style>
    .red {
      /* can't miss this! */
      /* shift input up 5px so we can see the bottom border */
      margin-top: -3px;
    }

    body {
      font-size: 10px;
      margin-top: 50px;
    }

    #wrap {
      display: block;
      margin: 0 auto;
      width: 200px;
    }
  	</style>
  </head>
  <body>
    <div class="wrap">
    </div>
    <textarea id="keyboard" name="name" rows="8" cols="70"></textarea>
  </body>
</html>
