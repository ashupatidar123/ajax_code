  @extends('admin.header')
  @section('title','Task')  
  
  @section('content')
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Task</h1>
          </div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Task Form</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
          <div class="card-header">
            <!-- <h3 class="card-title">Select2 (Default Theme)</h3> -->
            <h3 class="card-title">Add New Task</h3>
            <div class="card-tools">

              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>

            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-12">
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Add New Task</h3>
                  </div>
                  <div class="card-header">
                    <a href="{{url('webpanel/userList')}}" class="btn btn-primary"><i class="fa fa-list"></i> Go to list</a>
                  </div>

                  @if($alert = Session::get('alert'))
                    <div class="alert alert-{{$alert}} alert-block">
                      <button type="button" class="close" data-dismiss="alert">×</button> 
                      <strong>{{ Session::get('message') }}</strong>
                    </div>
                  @endif
                  <form role="form" method="post" enctype="multipart/form-data" id="formVal">
                    @csrf
                    <div class="card-body row">
                      
                      <div class="form-group col-md-4">
                        <label>User ID</label>
                        <input type="text" class="form-control" placeholder="Enter user id" name="user_id" autocomplete="off">
                        <span class="user_id text-danger error"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label>Task name</label>
                        <input type="text" class="form-control" placeholder="Enter task name" name="task_name" autocomplete="off">
                        <span class="task_name text-danger error"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label>Title</label>
                        <input type="text" class="form-control" placeholder="Title" name="title" autocomplete="off">
                        <span class="title text-danger error"></span>
                      </div>
                      <div class="form-group col-md-4">
                        <label>Task image</label>
                        <input type="file" class="form-control" name="task_image" autocomplete="off">
                      </div>
                      <span class="task_image"></span>
                      <div class="form-group col-md-12">
                        <button type="button" class="btn btn-info" onclick="return add_task()"><span class="spinner-border-sm addLorder"></span> Add task</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>            
          </div>
        </div>
        <!-- /.card -->

        
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection
  
  