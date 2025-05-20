@extends('layouts.master') 

@push('styles')
<style>
    .alert {
        display: flex;
        align-items: center;
        width: 100%;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.11);
        position: relative;
        padding: 0.5rem 1rem;
        margin-top: 5px;
    }

    .alert-white {
        background-image: linear-gradient(to bottom, #fff, #f9f9f9);
        border-color: #cacaca;
        color: #404040;
    }

    .alert-white .icon {
        text-align: center;
        width: 30px;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        padding-top: 15px;
        border-right: 1px solid #cacaca;
        background: #f9f9f9;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .alert-white .icon i {
        font-size: 14px;
        color: #fff;
    }

    .alert-white .icon:after {
        content: '';
        display: block;
        width: 10px;
        height: 10px;
        position: absolute;
        top: 50%;
        right: -5px;
        background: #fff;
        transform: rotate(45deg);
        border: 1px solid #cacaca;
        border-left: 0;
        border-bottom: 0;
    }

    .alert-danger {
        background-color: #f2dede;
        border-color: #e0b1b8;
        color: #b94a48;
    }

    .alert-white.alert-danger .icon,
    .alert-white.alert-danger .icon:after {
        border-color: #ca452e;
        background: #da4932;
    }

    .alert .btn-close {
        position: absolute;
        top: 50%;
        right: 0.5rem;
        transform: translateY(-50%);
        color: inherit;
    }
</style>
@endpush

@section('content')

  <div style="padding-top: 20px; padding-left: 5px;">
    <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;"> Update User: {{ $user->username }} </p>
  </div>

  <div class="container">
  <div class="row flex-lg-nowrap">
    <div class="col-12 col-lg-auto mb-3" style="width: 200px;">
      
    </div>

    <div class="col">
      <div class="row">
        <div class="col mb-3" style="padding-top: 20px;">
          <div class="card">
            <div class="card-body">
              <div class="e-profile">
                <div class="row">
                  <div class="col-12 col-sm-auto mb-3">
                  <form action="{{ route('update.user', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                      @csrf
                      @method('PUT')
                    <div class="mx-auto" style="width: 140px;">
                      <div class="d-flex justify-content-center align-items-center rounded" style="height: 140px; background-color: rgb(233, 236, 239);">
                        <img src="{{ asset($user->image_profile) }}" style="height: 140px; width: 140px;">
                      </div>
                    </div>
                  </div>
                  <div class="col d-flex flex-column flex-sm-row justify-content-between mb-3">
                    <div class="text-sm-left mb-2 mb-sm-0">
                      <h4 class="pt-sm-2 pb-1 mb-0 text-nowrap" style="font-family: 'Noto Serif Lao', serif;">{{ $user->name }}</h4>
                      <p class="mb-0" style="font-family: 'Noto Serif Lao', serif;">{{ $user->username }}</p>
                      <div class="text-muted" style="font-family: 'Noto Serif Lao', serif;"><small>Birthday: {{ $user->age }}</small></div>
                      <div class="mt-2">
                        <i class="fa fa-fw fa-camera"></i>
                          <label for="image_profile" class="btn btn-update" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
                              <i class="fa fa-fw fa-camera"></i> Change Photo
                          </label>
                          <input type="file" id="image_profile" name="image_profile" accept="image/*" style="display: none;">
                      </div>
                    </div>
                  </div>
                </div>
                <ul class="nav nav-tabs">
                  <li class="nav-item"><a href="" class="active nav-link" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Settings</a></li>
                </ul>
                <div class="tab-content pt-3">
                  <div class="tab-pane active">
                      <div class="row">
                        <div class="col">
                          <div class="row">
                            <div class="col">
                              <div class="form-group">
                                <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Full Name</label>
                                <input class="form-control" type="text" name="name" placeholder="{{ $user->name }}" value="{{ $user->name }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
                                @error('name')
                                    <div class="alert alert-danger alert-white rounded">
                                        <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                        <div class="icon">
                                            <i class="fa fa-times-circle"></i>
                                        </div>
                                        <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                        <a style="font-size: 14px;">{{ $message }}</a>
                                    </div>
                                @enderror
                              </div>
                            </div>
                            <div class="col">
                              <div class="form-group">
                                <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Username</label>
                                <input class="form-control" type="text" name="username" placeholder="{{ $user->username }}" value="{{ $user->username }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
                                @error('username')
                                    <div class="alert alert-danger alert-white rounded">
                                        <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                        <div class="icon">
                                            <i class="fa fa-times-circle"></i>
                                        </div>
                                        <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                        <a style="font-size: 14px;">{{ $message }}</a>
                                    </div>
                                @enderror
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group" style="padding-top: 10px;">
                                <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Email</label>
                                <input class="form-control" type="text" name="email" placeholder="{{ $user->email }}" value="{{ $user->email }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
                                @error('email')
                                    <div class="alert alert-danger alert-white rounded">
                                        <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                        <div class="icon">
                                            <i class="fa fa-times-circle"></i>
                                        </div>
                                        <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                        <a style="font-size: 14px;">{{ $message }}</a>
                                    </div>
                                @enderror
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-12 col-sm-6 mb-3">
                          <div class="row">
                            <div class="col">
                              <div class="form-group">
                                <label style="font-family: 'Noto Serif Lao', serif; padding-top: 10px; font-size: 14px;">Change Role</label>
                                <select class="form-control" name="role_type" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
                                  <option value="selected" disabled selected>{{ $user->role_type }}</option>
                                  <option value="STUDENT">STUDENT</option>
                                  <option value="TEACHER">TEACHER</option>
                                  <option value="ADMIN">ADMIN</option>
                                </select>
                                @error('role_type')
                                    <div class="alert alert-danger alert-white rounded">
                                        <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                        <div class="icon">
                                            <i class="fa fa-times-circle"></i>
                                        </div>
                                        <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                        <a style="font-size: 14px;">{{ $message }}</a>
                                    </div>
                                @enderror
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col" style="padding-top: 5px;">
                              <div class="form-group">
                                <label style="font-family: 'Noto Serif Lao', serif; padding-top: 10px; font-size: 14px;">Change Subscription</label>
                                <select class="form-control" name="subscription_type" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
                                  <option value="selected" disabled selected>{{ $user->subscription_type }}</option>
                                  <option value="FREEMIUM">FREEMIUM</option>
                                  <option value="PREMIUM">PREMIUM</option>
                                </select>
                                @error('subscription_type')
                                    <div class="alert alert-danger alert-white rounded">
                                        <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                        <div class="icon">
                                            <i class="fa fa-times-circle"></i>
                                        </div>
                                        <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                        <a style="font-size: 14px;">{{ $message }}</a>
                                    </div>
                                @enderror
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col">
                              <div class="form-group">
                                <input class="form-control" type="hidden" ></div>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-sm-5 offset-sm-1 mb-3">
                          <div class="mb-2"></div>
                          
                        </div>
                      </div>
                      <div class="row">
                        <div class="col d-flex justify-content-end">
                          <a href="http://127.0.0.1:8000/admin/users" style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">Back: Users</a>
                          <a href="http://127.0.0.1:8000/admin/addresses" style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">Back: Addresses</a>
                          <button class="btn btn-update" type="submit" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Save Changes</button>
                        </div>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-3 mb-3">
          
      </div>

    </div>
  </div>
  </div>
@endsection

@section('scripts')

<script>
    document.getElementById('change-photo-btn').addEventListener('click', function() {
        document.getElementById('profile-picture').click();
    });
</script>

@endsection