@extends('layouts.master')
@section('title','all-batches')
@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title">Batches</h4>
    </div>
    <div class="card-body table-responsive">
        <table class="table" style="width:100%" id="allBatchTable">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Company Name</th>
                    <th>Processed</th>
                    <th>Succeeded</th>
                    <th>Failed</th>
                    {{-- <th>Retry</th>
                    <th>Total</th> --}}
                    <th>Revenue</th>
                    <th>Type</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batches as $batch)
                <tr>
                    <td class="">
                        <div class="d-inline-flex">
                            <div class="p-1">
                                <a href="{{ url('batch-export/'.$batch->id) }}" class="btn btn-success btn-sm"><i class="bi bi-cloud-arrow-down-fill"></i></a>
                            </div>
                            <div class="p-1">
                                <a href="{{ url('batch-detail/'.$batch->id.'') }}" class="btn btn-danger btn-sm">detail</a>
                            </div>
                        </div>
                    </td>
                    <td>{{ $batch->id }}</td>
                    <td>{{ $batch->name }}</td>
                    <td>{{ $batch->cname }}</td>
                    <td>{{ $batch->processed }}</td>
                    <td>{{ $batch->succeeded }}</td>
                    <td>{{ $batch->failed }}</td>
                    {{-- <td>{{ $batch->retry }}</td>
                    <td>{{ $batch->total }}</td> --}}
                    <td> 
                        @if ($batch->dtotal == null || $batch->dtotal == 0)
                            {{ $batch->succeeded * $batch->btotal }}
                        @else
                            {{ $batch->succeeded * $batch->dtotal }}
                        @endif    
                    </td>

                     <td> 
                        @if ($batch->dtotal == null || $batch->dtotal == 0)
                            Bill
                        @else
                            Data
                        @endif    
                    </td>
                    <td>{{ $batch->created_at }}</td>
                    <td>{{ $batch->updated_at }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


@endsection
@section('script')
   <script>
        $('#allBatchTable').DataTable();
   </script>
@endsection