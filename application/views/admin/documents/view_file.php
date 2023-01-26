<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    #the-canvas {
      border: 1px solid black;
      direction: ltr;
    }

    #clipboards {
      clear: right;
      padding-top: 1em;
      display: none;
    }
  </style>
</head>

<body>
  <h1>Click to set signature starting position.</h1>
  <canvas id="the-canvas" onclick="getXYCordinates(event)"></canvas>
  <div id="clipboards"></div>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script type="text/javascript">
    var canvas;
    var context;
    var url = '<?= base_url('assets/pdf_uploads/') . $filename ?>';
    var propotionUnit = 0.2;
    var px;
    var py;
    var pw;
    var ph;
    var copyBounds;
    var offscreen;
    var signatureUrl = '<?= base_url('assets/signatures/') . $signatureFileName ?>';

    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.worker.min.js';

    // Asynchronous download of PDF
    var loadingTask = pdfjsLib.getDocument(url);
    loadingTask.promise.then(function(pdf) {
      // Fetch the first page
      var pageNumber = 1;
      pdf.getPage(pageNumber).then(function(page) {
        var width = page._pageInfo.view[2];
        var height = page._pageInfo.view[3];

        var scale = Math.round(width / height);
        var viewport = page.getViewport({
          scale: scale
        });

        canvas = document.getElementById('the-canvas');
        context = canvas.getContext('2d');
        canvas.height = viewport.height;
        canvas.width = viewport.width;
        // Render PDF page into canvas context
        var renderContext = {
          canvasContext: context,
          viewport: viewport
        };
        var renderTask = page.render(renderContext);
        renderTask.promise.then(function() {
          var str = '<?php echo $fileObj->signature_point; ?>';
          var res = str.split("x");

          defaultXYCordinates(res[0] * 2.83, res[1] * 2.83);
        });
      });
    }, function(reason) {
      console.error(reason);
    });



    function getXYCordinates(evt) {
      var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>',
        csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';
      var rect = canvas.getBoundingClientRect();
      var x = evt.clientX - rect.left;
      var y = evt.clientY - rect.top;
      // context.clearRect(px, py, pw, ph);
      context.drawImage(offscreen, copyBounds.x1, copyBounds.y1);
      // context.fillStyle = "#FF0000";
      // context.fillRect(x, y, 80, 30);

      var img = new Image();
      img.src = signatureUrl;
      img.onload = function() {
        copyBounds = {
          x1: x,
          y1: y,
          w: img.width * propotionUnit,
          h: img.height * propotionUnit
        };

        document.getElementById('c2').remove();
        offscreen = document.createElement('canvas');
        offscreen.setAttribute('id', 'c2');
        document.getElementById('clipboards').appendChild(offscreen); // Not necessary!
        offscreen.width = copyBounds.w;
        offscreen.height = copyBounds.h;
        offscreen.getContext('2d').drawImage(canvas, copyBounds.x1, copyBounds.y1, copyBounds.w, copyBounds.h, 0, 0, copyBounds.w, copyBounds.h);

        context.drawImage(img, x, y, img.width * propotionUnit, img.height * propotionUnit);
      };



      px = x;
      py = y;
      pw = img.width * propotionUnit;
      ph = img.height * propotionUnit;
      $.ajax({
        url: "<?= base_url('admin/upload_documents/update_signature_point/' . $fileObj->file_id) ?>",
        method: 'post',
        data: {
          [csrfName]: csrfHash,
          posX: x,
          posY: y,
        },
        success: function(data) {
          csrfName = data.csrfName;
          csrfHash = data.csrfHash;
          // alert('Signature Position Updated.')
        }
      });
    }

    function defaultXYCordinates(x, y) {
      var img = new Image();
      img.src = signatureUrl;
      img.onload = function() {

        copyBounds = {
          x1: x,
          y1: y,
          w: img.width * propotionUnit,
          h: img.height * propotionUnit
        };

        offscreen = document.createElement('canvas');
        offscreen.setAttribute('id', 'c2');
        document.getElementById('clipboards').appendChild(offscreen); // Not necessary!
        offscreen.width = copyBounds.w;
        offscreen.height = copyBounds.h;
        offscreen.getContext('2d').drawImage(canvas, copyBounds.x1, copyBounds.y1, copyBounds.w, copyBounds.h, 0, 0, copyBounds.w, copyBounds.h);


        context.drawImage(img, x, y, img.width * propotionUnit, img.height * propotionUnit);
        px = x;
        py = y;
        pw = img.width * propotionUnit;
        ph = img.height * propotionUnit;

      };


    }
  </script>
</body>

</html>