<style>

/* course-list */
/* 檢視popup */
        .overlay {
            display: none;
            position: fixed;
            top: 0px;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 999;
        }
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 50%;
            top: 55%;
            transform: translate(-50%, -50%);
            background-color: #fefefe;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 30%;
            height: 85vh;
            max-width: 1000px;
        }
        .popup-content {
            max-height: 80vh;
            overflow-y: auto;
            margin:20px
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
        }
        .imageBox{
            display: flex;
            max-width: 100%;
            height: 40vh;
            justify-content: center;
            margin-top: 10px
        }
        #courseImage {

            margin-bottom: 15px;
            border-radius: 5px;
            
        }
        .course-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }
        .course-info p {
            margin: 10px 0;
        }


/* create-course */
/* 上傳照片 */

        .photo-box {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        text-align: center;
        margin-bottom: 10px
        }

        .preview-container {
            width: 300px;
            height: 200px;
            border: 2px dashed #ccc;
            margin: 20px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        #preview {
            max-width: 100%;
            max-height: 100%;
        }

        #fileInput {
            margin: 20px 0;
        }

        #uploadBtn {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

</style>