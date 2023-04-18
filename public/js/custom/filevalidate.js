var _validFileExtensions = [".jpg", ".jpeg", ".doc", ".gif", ".png",".pdf"];
var _validMimeTypes = ["image/jpg", "image/jpeg", "application/doc", "image/gif", "image/png","application/pdf"];    
function ValidateSingleFile(oInput) {
       
   var MimeType = oInput.files[0].type;
   var filename = oInput.files[0].name;
   var filelenght = filename.substring(filename.indexOf(".")+1, filename.length).toLowerCase().length;
   
    if (oInput.type == "file" && filelenght == 3) {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
               
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    if(MimeType == "image/jpg" || MimeType == "image/jpeg" || MimeType == "application/doc" || MimeType == "image/gif" || MimeType == "image/png" || MimeType == "application/pdf") {
                    blnValid = true;
                    break;
                }
                }
            }
             
            if (!blnValid) {
                //alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                 //toastr.error("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                 toastr.error("File format not supported. Please upload files with .jpg, .jpeg, .doc, .gif, .pdf extensions only.");
                oInput.value = "";
                return false;
            }
        }
    } else {
        toastr.error("File format not supported. Please upload files with .jpg, .jpeg, .doc, .gif, .pdf extensions only.");
        oInput.value = ""; 
        return false;
    }
    return true;
}