@extends('layouts.dashboard')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <h1>{{ __('messages.countries') }}</h1>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#countryModal">
        {{ __('messages.add_country') }}
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.code') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($countries as $country)
                <tr>
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->code }}</td>
                    <td>
                        <button class="btn btn-warning edit-btn" 
                                data-id="{{ $country->id }}"
                                data-code="{{ $country->code }}"
                                data-name="{{ $country->name }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#countryModal">
                            {{ __('messages.edit') }}
                        </button>

                        <form action="{{ route('dashboard.countries.destroy', $country->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.confirm_delete_country') }}')">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="countryModal" tabindex="-1" aria-labelledby="countryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('messages.add_country') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="countryForm" action="{{ route('dashboard.countries.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="countryId" name="id">
                    <input type="hidden" id="methodInput" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                         <div class="mb-3">
                            <label for="code" class="form-label">{{ __('messages.code') }}</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('messages.close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('countryModal');
            const form = document.getElementById('countryForm');
            const modalTitle = document.getElementById('modalTitle');
            const countryId = document.getElementById('countryId');
            const codeInput = document.getElementById('code');
            const nameInput = document.getElementById('name');
            const methodInput = document.getElementById('methodInput');
            
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    modalTitle.textContent = '{{ __("messages.edit_country") }}';
                    countryId.value = this.dataset.id;
                    codeInput.value = this.dataset.code;
                    nameInput.value = this.dataset.name;
                    form.action = '{{ route("dashboard.countries.update", "") }}/' + this.dataset.id;
                    methodInput.value = 'PUT';
                });
            });
            
            modal.addEventListener('hidden.bs.modal', function() {
                modalTitle.textContent = '{{ __("messages.add_country") }}';
                form.reset();
                form.action = '{{ route("dashboard.countries.store") }}';
                methodInput.value = 'POST';
            });
        });
    </script>
@endpush