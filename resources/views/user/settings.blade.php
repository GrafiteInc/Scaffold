@extends('layouts.user')

@section('page-title', 'Settings')

@section('user_content')
    <div class="mt-3">
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-4 d-flex justify-content-center">
                            <div class="avatar shadow-sm border bmx-scale-1-hover bmx-drop-shadow-hover" style="background-image: url({{ auth()->user()->avatar_url }})"></div>
                        </div>
                        @if (auth()->user()->avatar)
                            <x-f-modal
                                message='<p class="mb-4">Are you sure you want to delete your avatar?</p>'
                                content="Confirm"
                                method="delete"
                                route="user.destroy.avatar"
                                triggerContent="<span class='fas fa-fw fa-trash'></span> Delete Avatar"
                                triggerClass="btn d-block w-100 btn-outline-danger mb-3"
                                :payload="['user' => auth()->id()]"
                                :options="['class' => 'btn btn-outline-primary float-end']"
                                :disableOnSubmit=true
                            />
                        @endif
                        <hr class="bmx-mt-6 mb-4">
                        <x-forms.user-delete-account></x-forms.user-delete-account>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <x-f-model
                    :form="\App\View\Forms\UserForm::class"
                    action="edit"
                    :model="$user"
                />
            </div>
        </div>
    </div>
@stop
