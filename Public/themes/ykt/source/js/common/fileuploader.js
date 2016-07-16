define(function(require, exports, module) {
	var $ = require('jquery');
/* Fine Uploader 2.1.2.  Andrew Valums and Ray Nicholus. Github project at http://ow.ly/eMrME */
var qq=qq||{};qq.extend=function(first,second){for(var prop in second){first[prop]=second[prop]}};qq.indexOf=function(arr,elt,from){if(arr.indexOf){return arr.indexOf(elt,from)}from=from||0;var len=arr.length;if(from<0){from+=len}for(;from<len;from++){if(from in arr&&arr[from]===elt){return from}}return -1};qq.getUniqueId=(function(){var id=0;return function(){return id++}})();qq.ie=function(){return navigator.userAgent.indexOf("MSIE")!=-1};qq.safari=function(){return navigator.vendor!=undefined&&navigator.vendor.indexOf("Apple")!=-1};qq.chrome=function(){return navigator.vendor!=undefined&&navigator.vendor.indexOf("Google")!=-1};qq.firefox=function(){return(navigator.userAgent.indexOf("Mozilla")!=-1&&navigator.vendor!=undefined&&navigator.vendor=="")};qq.windows=function(){return navigator.platform=="Win32"};qq.attach=function(element,type,fn){if(element.addEventListener){element.addEventListener(type,fn,false)}else{if(element.attachEvent){element.attachEvent("on"+type,fn)}}return function(){qq.detach(element,type,fn)}};qq.detach=function(element,type,fn){if(element.removeEventListener){element.removeEventListener(type,fn,false)}else{if(element.attachEvent){element.detachEvent("on"+type,fn)}}};qq.preventDefault=function(e){if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}};qq.insertBefore=function(a,b){b.parentNode.insertBefore(a,b)};qq.remove=function(element){element.parentNode.removeChild(element)};qq.contains=function(parent,descendant){if(parent==descendant){return true}if(parent.contains){return parent.contains(descendant)}else{return !!(descendant.compareDocumentPosition(parent)&8)}};qq.toElement=(function(){var div=document.createElement("div");return function(html){div.innerHTML=html;var element=div.firstChild;div.removeChild(element);return element}})();qq.css=function(element,styles){if(styles.opacity!=null){if(typeof element.style.opacity!="string"&&typeof(element.filters)!="undefined"){styles.filter="alpha(opacity="+Math.round(100*styles.opacity)+")"}}qq.extend(element.style,styles)};qq.hasClass=function(element,name){var re=new RegExp("(^| )"+name+"( |$)");return re.test(element.className)};qq.addClass=function(element,name){if(!qq.hasClass(element,name)){element.className+=" "+name}};qq.removeClass=function(element,name){var re=new RegExp("(^| )"+name+"( |$)");element.className=element.className.replace(re," ").replace(/^\s+|\s+$/g,"")};qq.setText=function(element,text){element.innerText=text;element.textContent=text};qq.children=function(element){var children=[],child=element.firstChild;while(child){if(child.nodeType==1){children.push(child)}child=child.nextSibling}return children};qq.getByClass=function(element,className){if(element.querySelectorAll){return element.querySelectorAll("."+className)}var result=[];var candidates=element.getElementsByTagName("*");var len=candidates.length;for(var i=0;i<len;i++){if(qq.hasClass(candidates[i],className)){result.push(candidates[i])}}return result};qq.obj2url=function(obj,temp,prefixDone){var uristrings=[],prefix="&",add=function(nextObj,i){var nextTemp=temp?(/\[\]$/.test(temp))?temp:temp+"["+i+"]":i;if((nextTemp!="undefined")&&(i!="undefined")){uristrings.push((typeof nextObj==="object")?qq.obj2url(nextObj,nextTemp,true):(Object.prototype.toString.call(nextObj)==="[object Function]")?encodeURIComponent(nextTemp)+"="+encodeURIComponent(nextObj()):encodeURIComponent(nextTemp)+"="+encodeURIComponent(nextObj))}};if(!prefixDone&&temp){prefix=(/\?/.test(temp))?(/\?$/.test(temp))?"":"&":"?";uristrings.push(temp);uristrings.push(qq.obj2url(obj))}else{if((Object.prototype.toString.call(obj)==="[object Array]")&&(typeof obj!="undefined")){for(var i=0,len=obj.length;i<len;++i){add(obj[i],i)}}else{if((typeof obj!="undefined")&&(obj!==null)&&(typeof obj==="object")){for(var i in obj){add(obj[i],i)}}else{uristrings.push(encodeURIComponent(temp)+"="+encodeURIComponent(obj))}}}if(temp){return uristrings.join(prefix)}else{return uristrings.join(prefix).replace(/^&/,"").replace(/%20/g,"+")}};var qq=qq||{};qq.FileUploaderBasic=function(o){var that=this;this._options={debug:false,action:"/server/upload",params:{},customHeaders:{},button:null,multiple:true,maxConnections:3,disableCancelForFormUploads:false,autoUpload:true,forceMultipart:false,allowedExtensions
:[],acceptFiles:null,sizeLimit:0,minSizeLimit:0,stopOnFirstInvalidFile:true,onSubmit:function(id,fileName){},onComplete:function(id,fileName,responseJSON){},onCancel:function(id,fileName){},onUpload:function(id,fileName,xhr){},onProgress:function(id,fileName,loaded,total){},onError:function(id,fileName,reason){},messages:{typeError:"禁止上传该格式文件，请上传以下格式文件:\r\n {extensions}.",sizeError:"{file} 过大, 最大 {sizeLimit}.",minSizeError:"{file} 太小，最小 {minSizeLimit}.",emptyError:"{file} 为空，请重选.",noFilesError:"没有选择文件.",onLeave:"正在上传文件，如果您离开页面，上传将被取消。"},showMessage:function(message){alert(message)},inputName:"qqfile"};qq.extend(this._options,o);this._wrapCallbacks();qq.extend(this,qq.DisposeSupport);this._filesInProgress=0;this._storedFileIds=[];this._handler=this._createUploadHandler();if(this._options.button){this._button=this._createUploadButton(this._options.button)}this._preventLeaveInProgress()};qq.FileUploaderBasic.prototype={log:function(str){if(this._options.debug&&window.console){console.log("[uploader] "+str)}},setParams:function(params){this._options.params=params},getInProgress:function(){return this._filesInProgress},uploadStoredFiles:function(){while(this._storedFileIds.length){this._filesInProgress++;this._handler.upload(this._storedFileIds.shift(),this._options.params)}},clearStoredFiles:function(){this._storedFileIds=[]},_createUploadButton:function(element){var self=this;var button=new qq.UploadButton({element:element,multiple:this._options.multiple&&qq.UploadHandlerXhr.isSupported(),acceptFiles:this._options.acceptFiles,onChange:function(input){self._onInputChange(input)}});this.addDisposer(function(){button.dispose()});return button},_createUploadHandler:function(){var self=this,handlerClass;if(qq.UploadHandlerXhr.isSupported()){handlerClass="UploadHandlerXhr"}else{handlerClass="UploadHandlerForm"}var handler=new qq[handlerClass]({debug:this._options.debug,action:this._options.action,forceMultipart:this._options.forceMultipart,maxConnections:this._options.maxConnections,customHeaders:this._options.customHeaders,inputName:this._options.inputName,demoMode:this._options.demoMode,onProgress:function(id,fileName,loaded,total){self._onProgress(id,fileName,loaded,total);self._options.onProgress(id,fileName,loaded,total)},onComplete:function(id,fileName,result){self._onComplete(id,fileName,result);self._options.onComplete(id,fileName,result)},onCancel:function(id,fileName){self._onCancel(id,fileName);self._options.onCancel(id,fileName)},onError:self._options.onError,onUpload:function(id,fileName,xhr){self._onUpload(id,fileName,xhr);self._options.onUpload(id,fileName,xhr)}});return handler},_preventLeaveInProgress:function(){var self=this;this._attach(window,"beforeunload",function(e){if(!self._filesInProgress){return}var e=e||window.event;e.returnValue=self._options.messages.onLeave;return self._options.messages.onLeave})},_onSubmit:function(id,fileName){if(this._options.autoUpload){this._filesInProgress++}},_onProgress:function(id,fileName,loaded,total){},_onComplete:function(id,fileName,result){this._filesInProgress--;if(!result.success){var errorReason=result.error?result.error:"上传失败";this._options.onError(id,fileName,errorReason)}},_onCancel:function(id,fileName){var storedFileIndex=qq.indexOf(this._storedFileIds,id);if(this._options.autoUpload||storedFileIndex<0){this._filesInProgress--}else{if(!this._options.autoUpload){this._storedFileIds.splice(storedFileIndex,1)}}},_onUpload:function(id,fileName,xhr){},_onInputChange:function(input){if(this._handler instanceof qq.UploadHandlerXhr){this._uploadFileList(input.files)}else{if(this._validateFile(input)){this._uploadFile(input)}}this._button.reset()},_uploadFileList:function(files){if(files.length>0){for(var i=0;i<files.length;i++){if(this._validateFile(files[i])){this._uploadFile(files[i])}else{if(this._options.stopOnFirstInvalidFile){return}}}}else{this._error("noFilesError","")}},_uploadFile:function(fileContainer){var id=this._handler.add(fileContainer);var fileName=this._handler.getName(id);if(this._options.onSubmit(id,fileName)!==false){this._onSubmit(id,fileName);if(this._options.autoUpload){this._handler.upload(id,this._options.params)}else{this._storeFileForLater(id)}}},_storeFileForLater:function(id){this._storedFileIds.push(id)},_validateFile:function(file){var name,size;if(file.value){name=file.value.replace(/.*(\/|\\)/,"")}else{name=(file.fileName!==null&&file.fileName!==undefined)?file.fileName:file.name;size=(file.fileSize!==null&&file.fileSize!==undefined)?file.fileSize:file.size}if(!this._isAllowedExtension(name)){this._error("typeError",name);return false}else{if(size===0){this._error("emptyError",name);return false}else{if(size&&this._options.sizeLimit&&size>this._options.sizeLimit){this._error("sizeError",name);return false}else{if(size&&size<this._options.minSizeLimit){this._error("minSizeError",name);return false}}}}return true},_error:function(code,fileName){var message=this._options.messages[code];function r(name,replacement){message=message.replace(name,replacement)}var extensions=this._options.allowedExtensions.join(", ");r("{file}",this._formatFileName(fileName));r("{extensions}",extensions);r("{sizeLimit}",this._formatSize(this._options.sizeLimit));r("{minSizeLimit}",this._formatSize(this._options.minSizeLimit));this._options.onError(null,fileName,message);this._options.showMessage(message)},_formatFileName:function(name){if(name.length>33){name=name.slice(0,19)+"..."+name.slice(-13)}return name},_isAllowedExtension:function(fileName){var ext=(-1!==fileName.indexOf("."))?fileName.replace(/.*[.]/,"").toLowerCase():"";var allowed=this._options.allowedExtensions;if(!allowed.length){return true}for(var i=0;i<allowed.length;i++){if(allowed[i].toLowerCase()==ext){return true}}return false},_formatSize:function(bytes){var i=-1;do{bytes=bytes/1024;i++}while(bytes>99);return Math.max(bytes,0.1).toFixed(1)+["kB","MB","GB","TB","PB","EB"][i]},_wrapCallbacks:function(){var self,safeCallback;self=this;safeCallback=function(callback,args){try{return callback.apply(self,args)}catch(exception){self.log("Caught "+exception+" in callback: "+callback)}};for(var prop in this._options){if(/^on[A-Z]/.test(prop)){(function(){var oldCallback=self._options[prop];self._options[prop]=function(){return safeCallback(oldCallback,arguments)}}())}}}};qq.FileUploader=function(o){qq.FileUploaderBasic.apply(this,arguments);qq.extend(this._options,{element:null,listElement:null,dragText:"拖放文件上传",extraDropzones:[],hideDropzones:true,disableDefaultDropzone:false,uploadButtonText:"上传一个文件",cancelButtonText:"取消",failUploadText:"上传失败",template:'<div class="qq-uploader">'+(!this._options.disableDefaultDropzone?'<div class="qq-upload-drop-area"><span>{dragText}</span></div>':"")+(!this._options.button?'<div class="qq-upload-button">{uploadButtonText}</div>':"")+(!this._options.listElement?'<ul class="qq-upload-list"></ul>':"")+"</div>",fileTemplate:'<li><div class="qq-progress-bar"></div><span class="qq-upload-spinner"></span><span class="qq-upload-finished"></span><span class="qq-upload-file"></span><span class="qq-upload-size"></span><a class="qq-upload-cancel" href="#">{cancelButtonText}</a><span class="qq-upload-failed-text">{failUploadtext}</span></li>',classes:{button:"qq-upload-button",drop:"qq-upload-drop-area",dropActive:"qq-upload-drop-area-active",dropDisabled:"qq-upload-drop-area-disabled",list:"qq-upload-list",progressBar:"qq-progress-bar",file:"qq-upload-file",spinner:"qq-upload-spinner",finished:"qq-upload-finished",size:"qq-upload-size",cancel:"qq-upload-cancel",failText:"qq-upload-failed-text",success:"qq-upload-success",fail:"qq-upload-fail",successIcon:null,failIcon:null},extraMessages:{formatProgress:"{percent}% / {total_size}",tooManyFilesError:"只能拖放一个文件"},failedUploadTextDisplay:{mode:"default",maxChars:50,responseProperty:"error",enableTooltip:true}});qq.extend(this._options,o);this._wrapCallbacks();qq.extend(this._options.messages,this._options.extraMessages);this._options.template=this._options.template.replace(/\{dragText\}/g,this._options.dragText);this._options.template=this._options.template.replace(/\{uploadButtonText\}/g,this._options.uploadButtonText);this._options.fileTemplate=this._options.fileTemplate.replace(/\{cancelButtonText\}/g,this._options.cancelButtonText);this._options.fileTemplate=this._options.fileTemplate.replace(/\{failUploadtext\}/g,this._options.failUploadText);this._element=this._options.element;this._element.innerHTML=this._options.template;this._listElement=this._options.listElement||this._find(this._element,"list");this._classes=this._options.classes;if(!this._button){this._button=this._createUploadButton(this._find(this._element,"button"))}this._bindCancelEvent();this._setupDragDrop()};qq.extend(qq.FileUploader.prototype,qq.FileUploaderBasic.prototype);qq.extend(qq.FileUploader.prototype,{clearStoredFiles:function(){qq.FileUploaderBasic.prototype.clearStoredFiles.apply(this,arguments);this._listElement.innerHTML=""},addExtraDropzone:function(element){this._setupExtraDropzone(element)},removeExtraDropzone:function(element){var dzs=this._options.extraDropzones;for(var i in dzs){if(dzs[i]===element){return this._options.extraDropzones.splice(i,1)}}},_leaving_document_out:function(e){return((qq.chrome()||(qq.safari()&&qq.windows()))&&e.clientX==0&&e.clientY==0)||(qq.firefox()&&!e.relatedTarget)},_storeFileForLater:function(id){qq.FileUploaderBasic.prototype._storeFileForLater.apply(this,arguments);var item=this._getItemByFileId(id);this._find(item,"spinner").style.display="none"},_find:function(parent,type){var element=qq.getByClass(parent,this._options.classes[type])[0];if(!element){throw new Error("元素未找到 "+type)}return element},_setupExtraDropzone:function(element){this._options.extraDropzones.push(element);this._setupDropzone(element)},_setupDropzone:function(dropArea){var self=this;var dz=new qq.UploadDropZone({element:dropArea,onEnter:function(e){qq.addClass(dropArea,self._classes.dropActive);e.stopPropagation()},onLeave:function(e){},onLeaveNotDescendants:function(e){qq.removeClass(dropArea,self._classes.dropActive)},onDrop:function(e){if(self._options.hideDropzones){dropArea.style.display="none"}qq.removeClass(dropArea,self._classes.dropActive);if(e.dataTransfer.files.length>1&&!self._options.multiple){self._error("tooManyFilesError","")}else{self._uploadFileList(e.dataTransfer.files)}}});this.addDisposer(function(){dz.dispose()});if(this._options.hideDropzones){dropArea.style.display="none"}},_setupDragDrop:function(){var self=this;if(!this._options.disableDefaultDropzone){var dropArea=this._find(this._element,"drop");this._options.extraDropzones.push(dropArea)}var dropzones=this._options.extraDropzones;var i;for(i=0;i<dropzones.length;i++){this._setupDropzone(dropzones[i])}if(!this._options.disableDefaultDropzone&&!qq.ie()){this._attach(document,"dragenter",function(e){if(qq.hasClass(dropArea,self._classes.dropDisabled)){return}dropArea.style.display="block";for(i=0;i<dropzones.length;i++){dropzones[i].style.display="block"}})}this._attach(document,"dragleave",function(e){if(self._options.hideDropzones&&qq.FileUploader.prototype._leaving_document_out(e)){for(i=0;i<dropzones.length;i++){dropzones[i].style.display="none"}}});qq.attach(document,"drop",function(e){if(self._options.hideDropzones){for(i=0;i<dropzones.length;i++){dropzones[i].style.display="none"}}e.preventDefault()})},_onSubmit:function(id,fileName){qq.FileUploaderBasic.prototype._onSubmit.apply(this,arguments);this._addToList(id,fileName)},_onProgress:function(id,fileName,loaded,total){qq.FileUploaderBasic.prototype._onProgress.apply(this,arguments);var item=this._getItemByFileId(id);if(loaded===total){var cancelLink=this._find(item,"cancel");cancelLink.style.display="none"}var size=this._find(item,"size");size.style.display="inline";var text;var percent=Math.round(loaded/total*100);if(loaded!=total){text=this._formatProgress(loaded,total)}else{text=this._formatSize(total)}this._find(item,"progressBar").style.width=percent+"%";qq.setText(size,text)},_onComplete:function(id,fileName,result){qq.FileUploaderBasic.prototype._onComplete.apply(this,arguments);var item=this._getItemByFileId(id);qq.remove(this._find(item,"progressBar"));if(!this._options.disableCancelForFormUploads||qq.UploadHandlerXhr.isSupported()){qq.remove(this._find(item,"cancel"))}qq.remove(this._find(item,"spinner"));if(result.success){qq.addClass(item,this._classes.success);if(this._classes.successIcon){this._find(item,"finished").style.display="inline-block";qq.addClass(item,this._classes.successIcon)}}else{qq.addClass(item,this._classes.fail);if(this._classes.failIcon){this._find(item,"finished").style.display="inline-block";qq.addClass(item,this._classes.failIcon)}this._controlFailureTextDisplay(item,result)}},_onUpload:function(id,fileName,xhr){qq.FileUploaderBasic.prototype._onUpload.apply(this,arguments);var item=this._getItemByFileId(id);if(qq.UploadHandlerXhr.isSupported()){this._find(item,"progressBar").style.display="block"}var spinnerEl=this._find(item,"spinner");if(spinnerEl.style.display=="none"){spinnerEl.style.display="inline-block"}},_addToList:function(id,fileName){var item=qq.toElement(this._options.fileTemplate);if(this._options.disableCancelForFormUploads&&!qq.UploadHandlerXhr.isSupported()){var cancelLink=this._find(item,"cancel");qq.remove(cancelLink)}item.qqFileId=id;var fileElement=this._find(item,"file");qq.setText(fileElement,this._formatFileName(fileName));this._find(item,"size").style.display="none";if(!this._options.multiple){this._clearList()}this._listElement.appendChild(item)},_clearList:function(){this._listElement.innerHTML="";this.clearStoredFiles()},_getItemByFileId:function(id){var item=this._listElement.firstChild;while(item){if(item.qqFileId==id){return item}item=item.nextSibling}},_bindCancelEvent:function(){var self=this,list=this._listElement;this._attach(list,"click",function(e){e=e||window.event;var target=e.target||e.srcElement;if(qq.hasClass(target,self._classes.cancel)){qq.preventDefault(e);var item=target.parentNode;while(item.qqFileId==undefined){item=target=target.parentNode}self._handler.cancel(item.qqFileId);qq.remove(item)}})},_formatProgress:function(uploadedSize,totalSize){var message=this._options.messages.formatProgress;function r(name,replacement){message=message.replace(name,replacement)}r("{percent}",Math.round(uploadedSize/totalSize*100));r("{total_size}",this._formatSize(totalSize));return message},_controlFailureTextDisplay:function(item,response){var mode,maxChars,responseProperty,failureReason,shortFailureReason;mode=this._options.failedUploadTextDisplay.mode;maxChars=this._options.failedUploadTextDisplay.maxChars;responseProperty=this._options.failedUploadTextDisplay.responseProperty;if(mode==="custom"){var failureReason=response[responseProperty];if(failureReason){if(failureReason.length>maxChars){shortFailureReason=failureReason.substring(0,maxChars)+"..."}qq.setText(this._find(item,"failText"),shortFailureReason||failureReason);if(this._options.failedUploadTextDisplay.enableTooltip){this._showTooltip(item,failureReason)}}else{this.log("'"+responseProperty+"' 服务器输出格式不正确")}}else{if(mode==="none"){qq.remove(this._find(item,"failText"))}else{if(mode!=="default"){this.log("failedUploadTextDisplay.mode 的值  '"+mode+"' 不可用")}}}},_showTooltip:function(item,text){item.title=text}});qq.UploadDropZone=function(o){this._options={element:null,onEnter:function(e){},onLeave:function(e){},onLeaveNotDescendants:function(e){},onDrop:function(e){}};qq.extend(this._options,o);qq.extend(this,qq.DisposeSupport);this._element=this._options.element;this._disableDropOutside();this._attachEvents()};qq.UploadDropZone.prototype={_dragover_should_be_canceled:function(){return qq.safari()||(qq.firefox()&&qq.windows())},_disableDropOutside:function(e){if(!qq.UploadDropZone.dropOutsideDisabled){if(this._dragover_should_be_canceled){qq.attach(document,"dragover",function(e){e.preventDefault()})}else{qq.attach(document,"dragover",function(e){if(e.dataTransfer){e.dataTransfer.dropEffect="none";e.preventDefault()}})}qq.UploadDropZone.dropOutsideDisabled=true}},_attachEvents:function(){var self=this;self._attach(self._element,"dragover",function(e){if(!self._isValidFileDrag(e)){return}var effect=qq.ie()?null:e.dataTransfer.effectAllowed;if(effect=="move"||effect=="linkMove"){e.dataTransfer.dropEffect="move"}else{e.dataTransfer.dropEffect="copy"}e.stopPropagation();e.preventDefault()});self._attach(self._element,"dragenter",function(e){if(!self._isValidFileDrag(e)){return}self._options.onEnter(e)});self._attach(self._element,"dragleave",function(e){if(!self._isValidFileDrag(e)){return}self._options.onLeave(e);var relatedTarget=document.elementFromPoint(e.clientX,e.clientY);if(qq.contains(this,relatedTarget)){return}self._options.onLeaveNotDescendants(e)});self._attach(self._element,"drop",function(e){if(!self._isValidFileDrag(e)){return}e.preventDefault();self._options.onDrop(e)})},_isValidFileDrag:function(e){if(qq.ie()){return false}var dt=e.dataTransfer,isSafari=qq.safari();return dt&&dt.effectAllowed!="none"&&(dt.files||(!isSafari&&dt.types.contains&&dt.types.contains("Files")))}};qq.UploadButton=function(o){this._options={element:null,multiple:false,acceptFiles:null,name:"file",onChange:function(input){},hoverClass:"qq-upload-button-hover",focusClass:"qq-upload-button-focus"};qq.extend(this._options,o);qq.extend(this,qq.DisposeSupport);this._element=this._options.element;qq.css(this._element,{position:"relative",overflow:"hidden",direction:"ltr"});this._input=this._createInput()};qq.UploadButton.prototype={getInput:function(){return this._input},reset:function(){if(this._input.parentNode){qq.remove(this._input)}qq.removeClass(this._element,this._options.focusClass);this._input=this._createInput()},_createInput:function(){var input=document.createElement("input");if(this._options.multiple){input.setAttribute("multiple","multiple")}if(this._options.acceptFiles){input.setAttribute("accept",this._options.acceptFiles)}input.setAttribute("type","file");input.setAttribute("name",this._options.name);qq.css(input,{position:"absolute",right:0,top:0,fontFamily:"Arial",fontSize:"118px",margin:0,padding:0,cursor:"pointer",opacity:0});this._element.appendChild(input);var self=this;this._attach(input,"change",function(){self._options.onChange(input)});this._attach(input,"mouseover",function(){qq.addClass(self._element,self._options.hoverClass)});this._attach(input,"mouseout",function(){qq.removeClass(self._element,self._options.hoverClass)});this._attach(input,"focus",function(){qq.addClass(self._element,self._options.focusClass)});this._attach(input,"blur",function(){qq.removeClass(self._element,self._options.focusClass)});if(window.attachEvent){input.setAttribute("tabIndex","-1")}return input}};qq.UploadHandlerAbstract=function(o){this._options={debug:false,action:"/upload.php",maxConnections:999,onProgress:function(id,fileName,loaded,total){},onComplete:function(id,fileName,response){},onCancel:function(id,fileName){},onUpload:function(id,fileName,xhr){}};qq.extend(this._options,o);this._queue=[];this._params=[]};qq.UploadHandlerAbstract.prototype={log:function(str){if(this._options.debug&&window.console){console.log("[uploader] "+str)}},add:function(file){},upload:function(id,params){var len=this._queue.push(id);var copy={};qq.extend(copy,params);this._params[id]=copy;if(len<=this._options.maxConnections){this._upload(id,this._params[id])}},cancel:function(id){this._cancel(id);this._dequeue(id)},cancelAll:function(){for(var i=0;i<this._queue.length;i++){this._cancel(this._queue[i])}this._queue=[]},getName:function(id){},getSize:function(id){},getQueue:function(){return this._queue},_upload:function(id){},_cancel:function(id){},_dequeue:function(id){var i=qq.indexOf(this._queue,id);this._queue.splice(i,1);var max=this._options.maxConnections;if(this._queue.length>=max&&i<max){var nextId=this._queue[max-1];this._upload(nextId,this._params[nextId])}}};qq.UploadHandlerForm=function(o){qq.UploadHandlerAbstract.apply(this,arguments);this._inputs={};this._detach_load_events={}};qq.extend(qq.UploadHandlerForm.prototype,qq.UploadHandlerAbstract.prototype);qq.extend(qq.UploadHandlerForm.prototype,{add:function(fileInput){fileInput.setAttribute("name",this._options.inputName);var id="qq-upload-handler-iframe"+qq.getUniqueId();this._inputs[id]=fileInput;if(fileInput.parentNode){qq.remove(fileInput)}return id},getName:function(id){return this._inputs[id].value.replace(/.*(\/|\\)/,"")},_cancel:function(id){this._options.onCancel(id,this.getName(id));delete this._inputs[id];delete this._detach_load_events[id];var iframe=document.getElementById(id);if(iframe){iframe.setAttribute("src","javascript:false;");qq.remove(iframe)}},_upload:function(id,params){this._options.onUpload(id,this.getName(id),false);var input=this._inputs[id];if(!input){throw new Error("上传失败")}var fileName=this.getName(id);params[this._options.inputName]=fileName;var iframe=this._createIframe(id);var form=this._createForm(iframe,params);form.appendChild(input);var self=this;this._attachLoadEvent(iframe,function(){self.log("iframe loaded");var response=self._getIframeContentJSON(iframe);self._options.onComplete(id,fileName,response);self._dequeue(id);delete self._inputs[id];setTimeout(function(){self._detach_load_events[id]();delete self._detach_load_events[id];qq.remove(iframe)},1)});form.submit();qq.remove(form);return id},_attachLoadEvent:function(iframe,callback){this._detach_load_events[iframe.id]=qq.attach(iframe,"load",function(){if(!iframe.parentNode){return}try{if(iframe.contentDocument&&iframe.contentDocument.body&&iframe.contentDocument.body.innerHTML=="false"){return}}catch(error){}callback()})},_getIframeContentJSON:function(iframe){try{var doc=iframe.contentDocument?iframe.contentDocument:iframe.contentWindow.document,response;var innerHTML=doc.body.innerHTML;this.log("converting iframe's innerHTML to JSON");this.log("innerHTML = "+innerHTML);if(innerHTML.slice(0,5).toLowerCase()=="<pre>"&&innerHTML.slice(-6).toLowerCase()=="</pre>"){innerHTML=doc.body.firstChild.firstChild.nodeValue}response=eval("("+innerHTML+")")}catch(err){response={success:false}}return response},_createIframe:function(id){var iframe=qq.toElement('<iframe src="javascript:false;" name="'+id+'" />');iframe.setAttribute("id",id);iframe.style.display="none";document.body.appendChild(iframe);return iframe},_createForm:function(iframe,params){var protocol=this._options.demoMode?"GET":"POST";var form=qq.toElement('<form method="'+protocol+'" enctype="multipart/form-data"></form>');var queryString=qq.obj2url(params,this._options.action);form.setAttribute("action",queryString);form.setAttribute("target",iframe.name);form.style.display="none";document.body.appendChild(form);return form}});qq.UploadHandlerXhr=function(o){qq.UploadHandlerAbstract.apply(this,arguments);this._files=[];this._xhrs=[];this._loaded=[]};qq.UploadHandlerXhr.isSupported=function(){var input=document.createElement("input");input.type="file";return("multiple" in input&&typeof File!="undefined"&&typeof FormData!="undefined"&&typeof(new XMLHttpRequest()).upload!="undefined")};qq.extend(qq.UploadHandlerXhr.prototype,qq.UploadHandlerAbstract.prototype);qq.extend(qq.UploadHandlerXhr.prototype,{add:function(file){if(!(file instanceof File)){throw new Error("上传失败，传送的数据对象不是文件 (qq.UploadHandlerXhr)")}return this._files.push(file)-1},getName:function(id){var file=this._files[id];return(file.fileName!==null&&file.fileName!==undefined)?file.fileName:file.name},getSize:function(id){var file=this._files[id];return file.fileSize!=null?file.fileSize:file.size},getLoaded:function(id){return this._loaded[id]||0},_upload:function(id,params){this._options.onUpload(id,this.getName(id),true);var file=this._files[id],name=this.getName(id),size=this.getSize(id);this._loaded[id]=0;var xhr=this._xhrs[id]=new XMLHttpRequest();var self=this;xhr.upload.onprogress=function(e){if(e.lengthComputable){self._loaded[id]=e.loaded;self._options.onProgress(id,name,e.loaded,e.total)}};xhr.onreadystatechange=function(){if(xhr.readyState==4){self._onComplete(id,xhr)}};params=params||{};params[this._options.inputName]=name;var queryString=qq.obj2url(params,this._options.action);var protocol=this._options.demoMode?"GET":"POST";xhr.open(protocol,queryString,true);xhr.setRequestHeader("X-Requested-With","XMLHttpRequest");xhr.setRequestHeader("X-File-Name",encodeURIComponent(name));xhr.setRequestHeader("Cache-Control","no-cache");if(this._options.forceMultipart){var formData=new FormData();formData.append(this._options.inputName,file);file=formData}else{xhr.setRequestHeader("Content-Type","application/octet-stream");xhr.setRequestHeader("X-Mime-Type",file.type)}for(key in this._options.customHeaders){xhr.setRequestHeader(key,this._options.customHeaders[key])}xhr.send(file)},_onComplete:function(id,xhr){if(!this._files[id]){return}var name=this.getName(id);var size=this.getSize(id);var response;this._options.onProgress(id,name,size,size);this.log("xhr - server response received");this.log("responseText = "+xhr.responseText);try{if(typeof JSON.parse==="function"){response=JSON.parse(xhr.responseText)}else{response=eval("("+xhr.responseText+")")}}catch(err){response={}}if(xhr.status!==200){this._options.onError(id,name,"XHR returned response code "+xhr.status)}this._options.onComplete(id,name,response);this._xhrs[id]=null;this._dequeue(id)},_cancel:function(id){this._options.onCancel(id,this.getName(id));this._files[id]=null;if(this._xhrs[id]){this._xhrs[id].abort();this._xhrs[id]=null}}});qq.DisposeSupport={_disposers:[],dispose:function(){var disposer;while(disposer=this._disposers.shift()){disposer()}},addDisposer:function(disposeFunction){this._disposers.push(disposeFunction)},_attach:function(){this.addDisposer(qq.attach.apply(this,arguments))}};
module.exports=qq;
});