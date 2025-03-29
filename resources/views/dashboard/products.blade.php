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

    <h1>{{ __('messages.products') }}</h1>

    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#productModal">
        {{ __('messages.add_product') }}
    </button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ __('messages.name') }}</th>
                <th>{{ __('messages.base_price') }}</th>
                <th>{{ __('messages.description') }}</th>
                <th>{{ __('messages.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->base_price }}</td>
                    <td>{{ $product->description ?? 'N/A' }}</td>
                    <td>
                        <button class="btn btn-warning edit-btn" data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}" data-base_price="{{ $product->base_price }}"
                            data-description="{{ $product->description }}" data-price_lists='@json($product->priceLists)'
                            data-bs-toggle="modal" data-bs-target="#productModal">
                            {{ __('messages.edit') }}
                        </button>

                        <form action="{{ route('dashboard.products.destroy', $product->id) }}" method="POST"
                            class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('{{ __('messages.confirm_delete_product') }}')">
                                {{ __('messages.delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">{{ __('messages.add_product') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="productForm" action="{{ route('dashboard.products.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="productId" name="id">
                    <input type="hidden" id="methodInput" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('messages.name') }}</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="base_price" class="form-label">{{ __('messages.base_price') }}</label>
                            <input type="number" step="0.01" class="form-control" id="base_price" name="base_price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">{{ __('messages.description') }}</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div id="priceListsContainer">
                            <label class="form-label">{{ __('messages.price_lists') }}</label>
                            <button type="button" class="btn btn-success btn-sm"
                                id="addPriceList">{{ __('messages.add_price_list') }}</button>
                            <div id="priceListsWrapper"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('messages.close') }}</button>
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
            const priceListsWrapper = document.getElementById('priceListsWrapper');
            const addPriceListBtn = document.getElementById('addPriceList');
            const modal = document.getElementById('productModal');
            const form = document.getElementById('productForm');
            const modalTitle = document.getElementById('modalTitle');
            const productId = document.getElementById('productId');
            const methodInput = document.getElementById('methodInput');
            let priceListIndex = 0;

            function createPriceListField(priceList = {}) {
                const div = document.createElement('div');
                div.classList.add('mb-3', 'border', 'p-2', 'rounded', 'position-relative');
                div.innerHTML = `
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.country') }}</label>
                            <select name="price_lists[${priceListIndex}][country_code]" class="form-control">
                                <option value="">{{ __('messages.all_countries') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->code }}" ${priceList.country_code == '{{ $country->code }}' ? 'selected' : ''}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.currency') }}</label>
                            <select name="price_lists[${priceListIndex}][currency_code]" class="form-control">
                                <option value="">{{ __('messages.all_currencies') }}</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->code }}" ${priceList.currency_code == '{{ $currency->code }}' ? 'selected' : ''}>
                                        {{ $currency->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.price') }}</label>
                            <input type="number" step="0.01" name="price_lists[${priceListIndex}][price]" class="form-control" value="${priceList.price || ''}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.start_date') }}</label>
                            <input type="date" name="price_lists[${priceListIndex}][start_date]" class="form-control" value="${priceList.start_date || ''}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.end_date') }}</label>
                            <input type="date" name="price_lists[${priceListIndex}][end_date]" class="form-control" value="${priceList.end_date || ''}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('messages.priority') }}</label>
                            <input type="number" name="price_lists[${priceListIndex}][priority]" class="form-control" value="${priceList.priority !== undefined && priceList.priority !== null ? priceList.priority : '1'}">
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 mt-1 me-1 remove-price-list">X</button>
                `;
                priceListsWrapper.appendChild(div);
                priceListIndex++;
            }

            addPriceListBtn.addEventListener('click', function() {
                createPriceListField();
            });

            priceListsWrapper.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-price-list')) {
                    e.target.closest('.mb-3').remove();
                }
            });

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('name').value = this.dataset.name;
                    document.getElementById('base_price').value = this.dataset.base_price;
                    document.getElementById('description').value = this.dataset.description || '';
                    productId.value = this.dataset.id;
                    modalTitle.textContent = '{{ __('messages.edit_product') }}';
                    form.action = '{{ route('dashboard.products.update', '') }}/' + this.dataset
                    .id;
                    methodInput.value = 'PUT';

                    priceListsWrapper.innerHTML = '';
                    const priceLists = JSON.parse(this.dataset.price_lists);
                    priceLists.forEach(priceList => createPriceListField(priceList));
                });
            });

            modal.addEventListener('hidden.bs.modal', function() {
                modalTitle.textContent = '{{ __('messages.add_product') }}';
                form.reset();
                form.action = '{{ route('dashboard.products.store') }}';
                methodInput.value = 'POST';
                priceListsWrapper.innerHTML = '';
                priceListIndex = 0;
            });
        });
    </script>
@endpush
