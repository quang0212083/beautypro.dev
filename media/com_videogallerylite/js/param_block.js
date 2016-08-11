parameters0 = new Array();
var srt_images = '';
var currentParentId;
var updateId;
var changeParent;
var deleteCount = 1;
function Add(sel, parentId, isNewProject)
{     
    if (isNewProject) {
      window.location.href='index.php?option=com_videogallery&view=videogallery&task=videogallery.addProject&sel='+sel+'&parentId='+parentId;
      return;        
    }
    if (par_images) {
        par_images[parentId].push(sel);
        var parentNode = document.getElementById("sel_img"+parentId);
        var li = document.createElement('li');
        li.id = "editthisimage_" + par_images[parentId].length + "_" + parentId;
        li.className = (par_images[parentId].length == 1) ? ("editthisimage" + parentId +" first") : ("editthisimage" + parentId);
        var img = document.createElement('img');
        img.id = "sel_img_" + par_images[parentId].length;       
        img.value =  sel;
        img.src = "../" + sel;
        var modal = document.createElement('a');
        modal.className = "modal-button";
        modal.id = par_images[parentId].length;
        modal.setAttribute('title', 'Image');
        
        var inputEdit = document.createElement('input');
        inputEdit.setAttribute('type', 'button');
        inputEdit.className = "edit-image";
        inputEdit.value = "Edit";
        modal.appendChild(inputEdit);
        var removeEl = document.createElement('a');
        removeEl.className = "remove-image";
        removeEl.id = par_images[parentId].length;
        removeEl.setAttribute('style', 'cursor: pointer');            
        li.appendChild(img);
        li.appendChild(modal);
        li.appendChild(removeEl);
        
        modal.onclick = function() {
            SqueezeBox.setContent('iframe',"index.php?option=com_media&view=images&tmpl=component&e_name=tempimage"); 
            getImage(url, parentId, modal.id);
             IeCursorFix();
             return false; 
        };
        
        removeEl.onclick =  function() {
           Remove(removeEl.id, parentId);
        };   
        parentNode.childNodes[1].insertBefore(li, parentNode.childNodes[1].childNodes[parentNode.childNodes[1].childNodes.length -3]);
        var image_url = document.getElementById("image_url"+parentId);
        image_url.value = setComma(par_images[parentId]);
        
    }


}
 function fillArray(sel)
{
    if (sel == 'sel_img')
    {
        for (i = 0; i < par_images.length; i++)
        {
            if (document.getElementById("sel_img_" + i) != "") {
                par_images[i] = document.getElementById("sel_img_" + i).getAttribute('value');
            }
        }
    }
  
}


function setComma(par_images) {
    var str = "";
    for (var i = 0; i < par_images.length; i++ ) {
        if (par_images[i] != ""){
            str+=par_images[i];
        }        
    }
    return str;
    
}

function fill(sel)
{
    document.getElementById(sel).innerHTML = '';

    if (sel == 'sel_img')
    {
        selInnerHTML_str = '';
        document.getElementById("image_url").value = '';

        for (i = 0; i < par_images.length; i++)
        {
            selInnerHTML_str += '<img id="' + sel + "_" + i + '" src="' + par_images[i] + '" onChange="Add(\'sel_img\')" value="' + par_images[i] + '" /> <a  class ="remove-image" onClick="Remove(' + i + ',\'sel_img\');" >a</a><br />';
            //document.getElementById("sel_img").value += par_images[i] + ";";
        }
        document.getElementById(sel).innerHTML = selInnerHTML_str;
    }
    else
    {
        document.getElementById("hid_" + sel).value = '';

        for (i = 0; i < parameters0[sel].length; i++)
        {
            var inpElement = document.createElement('input');
            inpElement.setAttribute('type', 'text');
            inpElement.setAttribute('style', 'width:200px;');
            inpElement.setAttribute('id', 'inp_' + sel + '_' + i);
            inpElement.setAttribute('value', parameters0[sel][i]);
            inpElement.setAttribute('onchange', 'Add(\'' + sel + '\')');

            var btnElement = document.createElement('input');
            btnElement.setAttribute('type', 'button');
            btnElement.setAttribute('value', 'X');
            btnElement.setAttribute('onclick', 'Remove(' + i + ',\'' + sel + '\')');

            document.getElementById(sel).appendChild(inpElement);
            document.getElementById(sel).appendChild(btnElement);
            document.getElementById(sel).appendChild(document.createElement('br'));

            document.getElementById("hid_" + sel).value += parameters0[sel][i] + "	";


        }

        if (document.getElementById("all_par_hid") != null)
        {
            document.getElementById("all_par_hid").value = "";
            for (keyVar in parameters0)
            {
                if (document.getElementById("hid_" + keyVar) != null)
                    document.getElementById("all_par_hid").value += keyVar + "@@:@@" + document.getElementById("hid_" + keyVar).value;
            }
            all_par_hid_temp_str = document.getElementById("all_par_hid").value;
            document.getElementById("all_par_hid").value = all_par_hid_temp_str.replace(/</g, "");
        }
    }
}

function loadHids()
{
    if (document.getElementById("all_par_hid") != null)
    {
        document.getElementById("all_par_hid").value = "";
        for (keyVar in parameters0)
        {
            if (document.getElementById("hid_" + keyVar) != null)
            {
                document.getElementById("hid_" + keyVar).value = '';
                for (i = 0; i < parameters0[keyVar].length; i++)
                {
                    document.getElementById("hid_" + keyVar).value += parameters0[keyVar][i] + "	";
                }
                document.getElementById("all_par_hid").value += keyVar + "@@:@@" + document.getElementById("hid_" + keyVar).value;
            }
        }
        all_par_hid_temp_str = document.getElementById("all_par_hid").value;
        document.getElementById("all_par_hid").value = all_par_hid_temp_str.replace(/</g, "");

    }

}

function Remove(i, parentId) {
    //fillArray(sel);
    
    
    var delEl = document.getElementById("editthisimage_" + i + "_" + parentId);
    var className = delEl.className.indexOf('first');
    if (className != -1) {        
        delEl.nextElementSibling.className += ' first'; 
    }
    
    delEl.remove();
    var parentNode = document.getElementById("sel_img" + parentId);
    par_images[parentId].splice(i-parentNode.getAttribute('deleteId'), 1);
    var count = parseInt(parentNode.getAttribute('deleteId')) + 1;
    
    parentNode.setAttribute('deleteId', count);
    
//     for (var k = 1; k <= par_images[parentId].length; k++) {
//         
//      var element = document.getElementById("editthisimage_" + k + "_" + parentId);
//       var query = element.querySelector("a.remove-image");
//       query.onclick = Remove(k, parentId);
//        
//    }
   
    var image_url = document.getElementById("image_url"+parentId);
    image_url.value = setComma(par_images[parentId]);   
    
}


function getImage(rootUrl, parentEl, updateId_, isNewProject)
{ 
    updateId = updateId_;
    currentParentId = parentEl;
  
    tinyMCE.editors.tempimage.onChange.dispatch = function()
    {       
        if (tinyMCE.editors.tempimage.contentDocument.getElementsByTagName("img").length > 0)
        {
            ImageSrc = tinyMCE.editors.tempimage.contentDocument.getElementsByTagName("img")[0].src;


            if (rootUrl.substr((rootUrl.length - 1), 1) == '/')
                ImageSrc = ImageSrc.substr(ImageSrc.indexOf(rootUrl) + rootUrl.length);
            else
                ImageSrc = ImageSrc.substr(ImageSrc.indexOf(rootUrl) + rootUrl.length + 1);

            if (updateId_ == null) {
               Add(ImageSrc, currentParentId, isNewProject);
            } else {
                Edit(ImageSrc, currentParentId, updateId );
            }           
            tinyMCE.editors.tempimage.contentDocument.body.innerHTML = '';
        }        
    }
   //el chgres
}
function Edit(src, parentId, elementId) {
    var elementLi = document.getElementById("editthisimage_" + elementId + "_" + parentId);
    var query = elementLi.querySelector("#sel_img_" + elementId);
    par_images[parentId][elementId - 1] = src;
     if (query) {
        query.src = "../" + src;
        query.value = src;
    }
    var image_url = document.getElementById("image_url"+parentId);
    image_url.value =par_images[parentId];    
}


function deletevote()
{
//    document.getElementById('adminForm').del_sel_votes.value = 1;
//    submitbutton('apply');
}

function checkedAll(checked)
{
    for (var i = 0; i < document.getElementById('adminForm').elements.length; i++) {
        if (document.getElementById('adminForm').elements[i].name != undefined)
            if (document.getElementById('adminForm').elements[i].name.indexOf("delete_vote") == 0)
                document.getElementById('adminForm').elements[i].checked = checked;
    }
}



function save_order()
{
    document.forms["adminForm"].submit();
}

