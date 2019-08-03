/**
 * 
 * @param {string} selector jquery需要的选择器 
 * @param {*} options 
 * img_src ==> 默认展示的图片src，可以不传
 * file_name ==> input标签的name与id属性值
 * callback ==> 回调函数，默认函数执行完毕后执行
 */
function DragImgUpload(selector,options) {
    this.me = $(selector);
    var defaultOpt = {
        boxWidth:'200px',
        boxHeight:'auto'
    }
    this.opts=$.extend(true, defaultOpt,{
    }, options);
    this.img_src = this.opts.img_src?this.opts.img_src:"/static/assets/img/upload.png"; //初始化背景图==》默认upload.png
    this.preview = $('<div id="preview"><img src="'+this.img_src+'" class="img-responsive"  style="width: 100%;height: auto;" alt="" title=""></div>');
    this.file_name = this.opts.file_name?this.opts.file_name:"uploadImg"; //设置input标签name和id==》默认uploadImg
    this.multiple = this.opts.multiple?this.opts.multiple:true; //是否允许上传多张图片===》默认true
    this.init();
    this.callback = this.opts.callback;
}

//定义原型方法
DragImgUpload.prototype = {
    init:function () {
        this.me.append(this.preview);
        this.cssInit();
        this.eventClickInit();
    },
    cssInit:function () {
        this.me.css({
            'width':this.opts.boxWidth,
            'height':this.opts.boxHeight,
            'border':'1px solid #cccccc',
            'padding':'10px',
            'cursor':'pointer'
        })
        this.preview.css({
            'height':'100%',
            'overflow':'hidden'
        })

    },
    onDragover:function (e) {
        e.stopPropagation();
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
    },
    onDrop:function (e) {
        var self = this;
        e.stopPropagation();
        e.preventDefault();
        var fileList = e.dataTransfer.files; //获取文件对象
        // do something upload
        if(fileList.length == 0){
            return false;
        }
        //检测文件是不是图片
        if(fileList[0].type.indexOf('image') === -1){
            alert("您拖的不是图片！");
            return false;
        }

        //拖拉图片到浏览器，可以实现预览功能
        var img = window.URL.createObjectURL(fileList[0]);
        var filename = fileList[0].name; //图片名称
        var filesize = Math.floor(((fileList[0].size)/1024)/1024);
        if(filesize>10){
            alert("上传大小不能超过10M.");
            return false;
        }

        self.me.find("img").attr("src",img);
        self.me.find("img").attr("title",filename);
        if(this.callback){
            this.callback(fileList);
        }
    },
    eventClickInit:function () {
        var self = this;
        this.me.unbind().click(function () {
            self.createImageUploadDialog();
            if($(self.fileInput).parent().length === 0){
                self.preview.append($(self.fileInput)); // 将input标签加入用户选定的传入的元素中
            }
        })
        var dp = this.me[0];
        dp.addEventListener('dragover', function(e) {
            self.onDragover(e);
        });
        dp.addEventListener("drop", function(e) {
            self.onDrop(e);
        });


    },
    onChangeUploadFile:function () {
        var fileInput = this.fileInput;
        var files = fileInput.files;
        if(!files.length > 0) return; // 用户取消选择图片就不再向下执行
        var file = files[0];
        var img = window.URL.createObjectURL(file);
        var filename = file.name;
        this.me.find("img").attr("src",img);
        this.me.find("img").attr("title",filename);
        if(this.callback){
            this.callback(this);
        }
    },
    createImageUploadDialog:function () {
        var fileInput = this.fileInput;
        if (!fileInput) {
            //创建临时input元素
            fileInput = $('<input>');
            fileInput.prop({
                'id':this.file_name,
                'type':'file',// 设置类型
                'name':this.file_name,// 设置name
                'accept':"image/*",// 限制文件类型为图片
                'multiple': true // 运行上传多个文件
            }).hide();
            fileInput.on('change',this.onChangeUploadFile.bind(this));
            this.fileInput = fileInput[0];
        }
        //触发点击input点击事件，弹出选择文件对话框
        fileInput.click();
    }




}