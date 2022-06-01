<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>todolist</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/fonts/bootstrap-icons.woff">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.2/font/bootstrap-icons.css">

</head>
<body>
    <div class="container p-4">
        <h1 class="h1 text-muted text-center m-5">Todos App</h1>
        <div class="input-group mb-3">
            <input type="text" id='inp-list' class="form-control" placeholder="What needs to be done?" aria-label="What needs to be done?" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" id="add" type="button" id="button-addon2">Add</button>
        </div>
        <div class="d-flex flex-row-reverse">
            <button class='btn btn-sm btn-danger m-1' id="btn-delete-all">Delete Checked List</button>
            <button class='btn btn-sm btn-outline-secondary m-1' id="btn-check-all">Cheked All List</button>
        </div>
        <ul class="list-group mt-1" id="list">
            
        </ul>  
    </div>

    <script src="/js/jquery.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){

            function initPage(response){
                let li ='';
                let checkedList=0;
                response.map((list)=>{
                    if(list.status == '1'){
                        checkedList++;
                    }
                    li +=`
                    <li class="list-group-item list-group-flush d-flex align-items-center">
                        <input class="form-check-input me-1" data-id="${list.id}" type="checkbox" ${list.status == '1' && 'checked' } aria-label="...">
                        <div class="input-group ml-3">
                            <span type="text" class="form-control border-0" aria-label="What needs to be done?" aria-describedby="button-addon2">${list.list}</span>
                            <button class="btn delete btn-sm" data-id="${list.id}" type="button" id="button-addon2"><i class="bi delete bi-trash" data-id="${list.id}"></i></button>
                        </div>
                    </li>
                    `;
                });

                if(response.length > 0){
                    $('#btn-check-all').removeAttr('disabled');
                }else{
                    $('#btn-check-all').attr('disabled','disabled');
                }

                if(checkedList > 0){
                    $('#btn-delete-all').removeAttr('disabled');
                   
                }else{
                    $('#btn-delete-all').attr('disabled','disabled');
                    
                }

                if(checkedList == response.length){
                    
                    $('#btn-check-all').text('Unchecked All List');
                    
                }else{
                    $('#btn-check-all').text('Cheked All List');
                    

                }

                $('#list').html(li);
            }

            function fetchData(){
                $('#list').html();
                $.ajax({
                    type: "GET",
                    url: "<?php echo base_url('/get_list') ?>",
                    dataType: 'json'
                }).then((response)=>{
                    console.log(response)
                    initPage(response)
                }).catch(err=>{
                    console.log(err)
                })
            }

            function postData(url,data){
                $.ajax({
                    type: "POST",
                    url:url,
                    data: data,
                    dataType: 'json'
                }).then((response)=>{
                    console.log(response)
                    fetchData()
                }).catch(err=>{
                    console.log(err)
                })
            }

            function addData(){
                let list = $('#inp-list').val();
                if(list == '' || list == null) {
                    alert('Please Fill Out The Field');
                    return false;
                }
                let data = {
                    list
                }
                let url =  "<?php echo base_url('/add') ?>";
                postData(url,data);
                $('#inp-list').val("");
            }

            $('#add').click(async function(){
                addData();
            });

            $('#inp-list').on('keyup', function (e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    addData();
                }
            });
            
            $('#list').click(function(e){
                let classList= e.target.classList;

                console.log(classList)
                if(classList.contains('form-check-input')){
                    console.log(e.target.checked)
                    let status=e.target.checked;
                    let id = e.target.dataset.id;
                    let data = {
                        id,status
                    }
                    console.log(data)
                    let url =  "<?php echo base_url('/update') ?>";
                    postData(url,data);
                }

                if(classList.contains('delete')){
                    let conf= confirm('Are you sure want to delete this list?');
                    if(!conf) return false;
                    let id = e.target.dataset.id;
                    let data = {
                        id
                    }
                    console.log(data)
                    let url =  "<?php echo base_url('/delete') ?>";
                    postData(url,data);
                }
                
            });

            $('#btn-check-all').click(function(){
                let btnText=$('#btn-check-all').text();
                let conf= confirm(`Are you sure want ${btnText}?`);
                if(!conf) return false;
                let data = {
                    status : 1
                }
                console.log("btnText",btnText);
                if(btnText == 'Unchecked All List'){
                    data.status = 0;
                }

                let url =  "<?php echo base_url('/checkall') ?>";
                postData(url,data);
            });

            $('#btn-delete-all').click(function(){
                
                let conf= confirm('Are you sure want to delete all data?');
                if(!conf) return false;

                let url =  "<?php echo base_url('/deleteall') ?>";
                postData(url);
            });

            fetchData();
        })
    </script>
</body>
</html>