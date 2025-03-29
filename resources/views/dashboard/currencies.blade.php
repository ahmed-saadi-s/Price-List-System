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
    
    <h1>{{ __('messages.currencies') }}</h1>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#currencyModal">
        {{ __('messages.add_currency') }}
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
            @foreach ($currencies as $currency)
                <tr>
                    <td>{{ $currency->name }}</td>
                    <td>{{ $currency->code }}</td>
                    <td>
                        <button class="btn btn-warning edit-btn" 
                                data-id="{{ $currency->id }}"
                                data-code="{{ $currency->code }}"
                                data-name="{{ $currency->name }}"
                                data-bs-toggle="modal" 
                                data-bs-target="#currencyModal">
                            {{ __('messages.edit') }}
                        </button>

                        <form action="{{ route('dashboard.currencies.destroy', $currency->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('{{ __('messages.confirm_delete_currency') }}')">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="currencyModal" tabindex="-1" aria-labelledby="currencyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('messages.add_currency') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="currencyForm" action="{{ route('dashboard.currencies.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="currencyId" name="id">
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
            const modal = document.getElementById('currencyModal');
            const form = document.getElementById('currencyForm');
            const modalTitle = document.getElementById('modalTitle');
            const currencyId = document.getElementById('currencyId');
            const codeInput = document.getElementById('code');
            const nameInput = document.getElementById('name');
            const methodInput = document.getElementById('methodInput');
            
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    modalTitle.textContent = '{{ __("messages.edit_currency") }}';
                    currencyId.value = this.dataset.id;
                    codeInput.value = this.dataset.code;
                    nameInput.value = this.dataset.name;
                    form.action = '{{ route("dashboard.currencies.update", "") }}/' + this.dataset.id;
                    methodInput.value = 'PUT';
                });
            });
            
            modal.addEventListener('hidden.bs.modal', function() {
                modalTitle.textContent = '{{ __("messages.add_currency") }}';
                form.reset();
                form.action = '{{ route("dashboard.currencies.store") }}';
                methodInput.value = 'POST';
            });
        });
    </script>
@endpush
