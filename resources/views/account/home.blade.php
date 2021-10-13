@extends('layouts.master')
@section('title', 'fetch-account')
@section('content')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="{{ url('create-account') }}" class="btn btn-success">
                    Create Customer +
                </a>  
                {{-- <a href="{{ url('create-service') }}" class="btn btn-primary">
                    Create Service +
                </a>                --}}
            </div>  

              @if(session('account-status'))
              <div class="bs-example">
                <div class="alert alert-success">                    
                    <strong>{{ session('account-status') }}</strong>                             
                </div>
                    
              </div>
              @endif

              
            <!-------------------------------------------------------------->
            <div class="card-body">
                <div class="col-md-12">
                    <div class="table-responsive">
                    <table class="table" style="width:100%" id='userTable'>
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Logo</th>
                            <th>Merchant Name</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            {{-- <th>Date</th> --}}
                            {{-- <th>Service Name</th>
                            <th>Amount</th> --}}
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($datas as $data)
                            
                            <tr>
                                <td> {{$loop->iteration}} </td>
                                <td> @if ($data->logo)
                                    <img src="{{asset($data->logo)}}" alt="" class="img-fluid" width="60px" height="60px">
                                @else
                                    <small width="100px" height="100px">No Logo</small>
                                @endif </td>
                                <td> {{ $data->name }} </td>
                                <td> {{ $data->usrname }} </td>
                                <td> {{ $data->email }} </td>
                                <td> {{ $data->phone_number }} </td>
                                {{-- <td> {{ $data->updated_at }} </td> --}}
                                {{-- <td>{{ $service->name }}</td>
                                <td>{{ $service->amount }} --}}
                                <td>
                                    
                                    <button class="btn btn-sm bg-warning icon text-white btnEdit" data-cid="{{$data->id}}">Edit</button>
                                    
                                
                                    
                                </td>
                            </tr>
                        
                        @endforeach
                        </tbody>
                    </table>
                </div>
                </div>
                
                
            </div>
        </div>

    </section>
@endsection

@section('script')
    <script>
        
        $(document).ready(function () {
            $('#userTable').DataTable();
        });
        
        $('.btnEdit').click(function() { 
            let id = $(this).attr('data-cid');
            axios.post('{{ route("edit-account") }}', {
                id
            }).then(({data}) => {
            
                Swal.fire({
                title: 'User Edit Form',
                html: `
                    <input id="cid" type="hidden" value="${data[0].id}">
                    <input id="email" type="hidden" value="${data[0].email}">
                    <input id="oldlogo" type="hidden" value="${data[0].logo}"><br>
                    <label style="">User Name</label>  :<input id="username" type="text" class="swal2-input" style="margin: 15px 15px;" value="${data[0].usrname}"><br>

                    <label style="margin-right: 36px">Phone</label>  :<input id="phone_number" type="text" class="swal2-input" style="margin: 15px 14px;" value="${data[0].phone_number}"><br>

                    <label style="margin-right: 20px">Address</label>  :<input id="address" type="text" class="swal2-input" style="margin: 15px 13px;" value="${data[0].address}"><br>

                    <label style="margin-right: 45px">Logo</label>  :<input id="logo" type="file" accept="image/png, image/jpg, image/jpeg" style="margin: 15px 11px;width:299px"><br>

                    
                `,
                confirmButtonText: 'Save',
                showDenyButton: true,
                denyButtonText: 'Cancel',
                allowOutsideClick: false,
                focusConfirm: false,
                preConfirm: () => {
                    const id = Swal.getPopup().querySelector('#cid').value
                    const username = Swal.getPopup().querySelector('#username').value
                    const address = Swal.getPopup().querySelector('#address').value
                    const phone_number = Swal.getPopup().querySelector('#phone_number').value
                    const email = Swal.getPopup().querySelector('#email').value
                    const logo = Swal.getPopup().querySelector('#logo').files[0]
                    const oldlogo = Swal.getPopup().querySelector('#oldlogo').value
                    if (!username || !address || !phone_number) {
                        Swal.showValidationMessage(`Invalid Information!`)
                    }
                    else if (username.length < 5) {
                        Swal.showValidationMessage(`Username must be at least 6 characters!`)
                    } 
                    else if (phone_number.toString().length < 8 || phone_number.toString().length > 13){
                        Swal.showValidationMessage(`Phone Number must have between 8 and 13 characters!`)
                    }
                    return { id,username,address,phone_number, email, logo, oldlogo }
                }
                }).then((result) => {
                    if (result.isConfirmed) {

                        if (data[0].usrname != result.value.username || data[0].phone_number != result.value.phone_number || data[0].address != result.value.address || result.value.logo) {

                            let formData = new FormData();

                            formData.append('id', result.value.id);
                            formData.append('username', result.value.username);
                            formData.append('address', result.value.address);
                            formData.append('phone', result.value.phone_number);
                            formData.append('email', result.value.email);
                            if (result.value.logo) {
                                formData.append('logo', result.value.logo, result.value.logo.name);
                            }
                            formData.append('oldlogo', result.value.oldlogo);
                            
                            axios({
                                method: "post",
                                url: '{{ route("update-account") }}',
                                data: formData,
                                headers: { "Content-Type": "multipart/form-data" },
                            })
                            .then(({data}) => {
                                
                                if (data == "success") {
                                    Swal.fire({
                                        icon: 'success',                                    
                                        text: 'Successfully Update!',
                                        confirmButtonText: 'OK',
                                        allowOutsideClick: false,
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    })
                                }else{
                                    Swal.fire({
                                        icon: 'error',                                    
                                        text: data,
                                    })
                                }
                            })
                        }else{
                            Swal.fire({
                                icon: 'info',                                    
                                text: 'There is no changes!',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false,
                            })
                        }

                        
                    }
                })

            })

        });

        
    </script>
@endsection