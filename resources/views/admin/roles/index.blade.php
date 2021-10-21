@extends('layouts.admin')

@section('page-title', 'Admin: Roles')

@section('admin_content')

    <div class="row">
        <div class="col-md-12">
            <div class="btn-toolbar justify-content-end">
                <a class="btn btn-primary" href="{{ url('admin/roles/create') }}">
                    <span class="fas fa-fw fa-plus"></span>
                    Create Role
                </a>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-md-12">
            @if ($roles->isEmpty())
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <span>No roles listed.</span>
                    </div>
                </div>
            @else
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="m-0">Roles</h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless m-0 p-0">
                            <tbody>
                                @foreach($roles as $role)
                                    <tr>
                                        <td>{{ $role->label }}</td>
                                        <td width="180px" class="text-right">
                                            <div class="btn-toolbar justify-content-end">
                                                <a class="btn btn-outline-primary btn-sm" href="{{ url('admin/roles/'.$role->id.'/edit') }}">
                                                    <span class="fas fa-fw fa-pencil-alt"></span> Edit
                                                </a>

                                                @if ($role->name !== 'admin')
                                                    {!! app(\App\Http\Forms\RoleForm::class)->confirm('Are you sure you want to delete this role?', 'confirmation')->delete($role) !!}
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

@stop
