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
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-calendar"></i></span>

              <div class="info-box-content">
                <span class="info-box-text text-bold" style="font-size: 23px">
                    {{__('Month')}}
                </span>
                <span class="info-box-number" style="font-size: 23px">
                    {{ \Carbon\Carbon::now()->month }}/{{ \Carbon\Carbon::now()->year }}
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-12 col-sm-6 col-md-3">
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

          <div class="col-12 col-sm-6 col-md-3">
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
          <div class="col-12 col-sm-6 col-md-3">
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
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title text-bold" style="font-size: 23px">{{__('Inventory Chart')}}</h3>
                  <!-- <a href="javascript:void(0);">View Report</a> -->
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <!-- <span class="text-bold text-lg">820</span> -->
                    <span>{{__('Unit')}} : Box</span>
                  </p>
                  <!-- <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 12.5%
                    </span>
                    <span class="text-muted">Since last week</span>
                  </p> -->
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="visitors-chart" height="400"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-center">
                  <span class="mr-3">
                    <i class="fas fa-square text-warning"></i> {{__('Actual Inventory')}}
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> {{__('Inventory Target')}}
                  </span>
                </div>
              </div>
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title text-bold" style="font-size: 23px">{{__('Import And Export Chart')}}</h3>
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
