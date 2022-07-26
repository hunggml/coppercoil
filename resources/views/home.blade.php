@extends('layouts.main')

@section('content')

@push('myCss')
    <style type="text/css">
        .info-box {
            min-height: 120px !important;
        }
        .info-box-icon {
            width: 100px !important;
        }
        .info-box-text {
            overflow: visible !important;
        }
    </style>
@endpush

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="row">
          <div class="col-12">
            <div class="info-box mb-3 row">
             <div class="col-4 row">
                <div class="form-group col-md-6">
									<label>{{__('Choose')}} {{ __('Warehouse') }}</label>
									<select class="custom-select select2" name="materials">
										<option value="">
											{{__('Choose')}} {{__('Warehouse')}}
										</option>
									</select>
								</div>
								<div class="form-group col-md-3">
                <label>{{__('Start')}}</label>
                <input type="Date" class="form-control">
								</div>
                <div class="form-group col-md-3">
                <label>{{__('Begin')}}</label>
                <input type="Date" class="form-control">
								</div>
                <div class="form-group col-md-6">
									<label>{{__('Choose')}} {{ __('Materials') }}</label>
									<select class="custom-select select2" name="materials">
										<option value="">
											{{__('Choose')}} {{__('Symbols')}}
										</option>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label>{{__('Choose')}} {{__('Unit')}}</label>
									<select class="custom-select select2" name="type">
										<option value="">
											{{__('Box')}}
										</option>
										<option value="">
											{{__('Kg')}}
										</option>
									</select>
								</div>
                <hr>
                <div class="col-6">
                  GAP <button class="btn" style="background:#b3e6ff"></button> : 1000
                </div>
                <div class="col-6">
                  GAP <button class="btn" style="background:#ffb3b3"></button> : 4000
                </div>
                <div class="col-6">
                  GAP <button class="btn" style="background:#ffffcc"></button> : 3000
                </div>
                <div class="col-6">
                 
                </div>
             </div>
             <div class="col-8">
              <table class="table table-bordered text-nowrap w-100" width="100%">
		              <thead>
		                		<th>{{__('STT')}}</th>
								        <th>{{__('Name')}} {{ __('Warehouse') }}</th>
		                		<th>{{__('Hàng Về')}}</th>
		                		<th>{{__('Nhập')}}</th>
		                		<th>{{__('Xuất')}}</th>
                        <th>{{__('Sử Dụng')}}</th>
                        <th>{{__('Hủy')}}</th>
		                		<th>{{__('Tồn')}}</th>
		                	</thead>
		                	<tbody>
		                		<tr>
                          <td>1</td>
                          <td>Đồng Chính</td>
                          <td style="background:#b3e6ff">1000</td>
                          <td style="background:#b3e6ff">1000</td>
                          <td style="background:#ffffcc">200</td>
                          <td style="">X</td>
                          <td style="">X</td>
                          <td style="">800</td>
                        </tr>
                        <tr>
                          <td rowspan="2">2</td>
                          <td>Coil SM</td>
                          <td>X</td>
                          <td style="background:#ffffcc">100</td>
                          <td style="background:#ffb3b3">90</td>
                          <td style="">X</td>
                          <td style="">X</td>
                          <td>10</td>
                        </tr>
                        <tr>
                          <!-- <td>2</td> -->
                          <td>Máy Sản Xuất Kho SM</td>
                          <td>X</td>
                          <td style="background:#ffb3b3">90</td>
                          <td>X</td>
                          <td>50</td>
                          <td>20</td>
                          <td>20</td>
                        </tr>
                        <tr>
                          <td  rowspan="2">3</td>
                          <td>Coil MM</td>
                          <td>X</td>
                          <td style="background:#ffffcc">100</td>
                          <td style="background:#ffb3b3">90</td>
                          <td style="">X</td>
                          <td style="">X</td>
                          <td>10</td>
                        </tr>
                        <tr>
                          <!-- <td>3</td> -->
                          <td>Máy Sản Xuất Kho MM</td>
                          <td>X</td>
                          <td style="background:#ffb3b3">90</td>
                          <td>X</td>
                          <td>50</td>
                          <td>20</td>
                          <td>20</td>
                        </tr>
		                	</tbody>
		            </table>
             </div>
             
            </div>
            <!-- /.info-box -->
          </div>
         
          
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-download"></i></span>

              <div class="info-box-content">
                <a href="#" style="color: black"><span class="info-box-text text-bold" style="font-size: 23px">{{__('Import')}}</span></a>
                <span class="info-box-number" style="font-size: 23px" id="importNow">2,760</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->

          <!-- fix for small devices only -->
          <div class="clearfix hidden-md-up"></div>

          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
              <span class="info-box-icon bg-success elevation-1"><i class="fas fa-upload"></i></span>

              <div class="info-box-content">
                <a href="#" style="color: black"><span class="info-box-text text-bold" style="font-size: 23px">{{__('Export')}}</span></a>
                <span class="info-box-number" style="font-size: 23px" id="exportNow">760</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-4">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                  <i class="fas fa-archive"></i>
                </span>

              <div class="info-box-content">
                <a href="#" style="color: black">
                  <span class="info-box-text text-bold" style="font-size: 23px">
                  {{__('Stock')}}
                </span>
              </a>
                <span class="info-box-number" style="font-size: 23px" id="inventoriesNow">2,000</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <br>
        <br>
        <!-- <br> -->
        <div class="row">
          
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title text-bold" style="font-size: 23px">{{__('Biểu Đồ Nhập Xuất Tồn')}}</h3>
                  <!-- <a href="javascript:void(0);">View Report</a> -->
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <!-- <span class="text-bold text-lg">$18,230.00</span> -->
                    <span>{{__('Unit')}} : Box</span>
                  </p>
                  <!-- <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 33.1%
                    </span>
                    <span class="text-muted">Since last month</span>
                  </p> -->
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="sales-chart" height="400"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-center">
                  <span class="mr-3">
                    <i class="fas fa-square text-danger"></i> {{__('Import')}}
                  </span>
                  <span>
                    <i class="fas fa-square text-success"></i> {{__('Export')}}
                  </span>
                  <span>
                    <i class="fas fa-square text-warning"></i> {{__('Stock')}}
                  </span>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>

          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title text-bold" style="font-size: 23px">{{__('Biểu Đồ Tồn Nguyên vật Liệu')}}</h3>
                  <!-- <a href="javascript:void(0);">View Report</a> -->
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <!-- <span class="text-bold text-lg">$18,230.00</span> -->
                    <span>{{__('Unit')}} : Box</span>
                  </p>
                  <!-- <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 33.1%
                    </span>
                    <span class="text-muted">Since last month</span>
                  </p> -->
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="sales-chart1" height="400"></canvas>
                </div>
                <div class="d-flex flex-row justify-content-center">
                  <span class="mr-3">
                    <i class="fas fa-square text-danger"></i> {{__('NVL1')}}
                  </span>
                  <span>
                    <i class="fas fa-square text-success"></i> {{__('NVL2')}}
                  </span>
                  <span>
                    <i class="fas fa-square text-warning"></i> {{__('NVL3')}}
                  </span>
                </div>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
        </div>



        </div>
    </div>
</div>
@endsection

@push('scripts')
  <script src="dist/js/pages/dashboard3.js"></script>
  <script>
    $('.select2').select2()
    $('.setting-account').on('click', function()
    {
      let dataSend = {
        'id' : '2',
        'username' : 'admin',
        'pos' : 'A1-1-1',
        'data' : '[{"LabelID":"78","Quantity":"100.0"},{"LabelID":"70","Quantity":"100.0"}]',
      }

      let time;

      $.ajax({
        method  : 'get',
        url     : window.location.origin + '/api/import-inventory',
        data    : dataSend,
        dataType: 'json',
        success : function(data) {
          console.log(data)
        },
        error : function(err) {
          console.log(err)
        }
      })
    });
  </script>
@endpush
