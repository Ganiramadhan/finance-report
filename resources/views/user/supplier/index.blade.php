@extends('layouts.admin.appUser', ['title' => 'Supplier'])

@section('content')
    <div class="section-header">
        <h1><i class="fas fa-truck" style="font-size: 1em;"></i> Data Supplier</h1>
    </div>


    <div class="row">
        <div class="col-6">
            <div class="search-element">
                <input class="form-control" placeholder="Cari Supplier" aria-label="Search" data-width="250" name="search"
                    id="search">
            </div>
        </div>
        {{-- <div class="col-6 text-right">
            <h5 class="mb-0"></h5>
            <a href="{{ route('supplier.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Tambah Supplier
            </a>
        </div> --}}
    </div>

    <hr />

    <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered" style="width:100%">
            <thead class="table-primary">
                <tr>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Sisa Utang</th>
                    <th>Nomor Telepon</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody id="supplier-list">
                @if ($suppliers->count() > 0)
                    @foreach ($suppliers as $supplier)
                        <tr>
                            <td class="align-middle">{{ $loop->iteration }}</td>
                            <td class="align-middle">{{ $supplier->nama }}</td>
                            <td class="align-middle">{{ 'Rp ' . number_format($supplier->saldo_awal_utang, 0, ',', '.') }}
                            </td>
                            <td class="align-middle">{{ $supplier->no_telepon }}</td>
                            <td class="align-middle">{{ $supplier->alamat }}</td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="6">Supplier not found</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div id="pagination" class="d-flex justify-content-center mt-3">
        <ul class="pagination">
            <li class="page-item {{ $suppliers->previousPageUrl() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $suppliers->previousPageUrl() }}" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            @for ($i = 1; $i <= $suppliers->lastPage(); $i++)
                <li class="page-item {{ $i == $suppliers->currentPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $suppliers->url($i) }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item {{ $suppliers->nextPageUrl() ? '' : 'disabled' }}">
                <a class="page-link" href="{{ $suppliers->nextPageUrl() }}" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            var currentPage = {{ $suppliers->currentPage() }};

            $('#search').on('keyup', function() {
                var query = $(this).val();
                $.ajax({
                    url: "{{ route('supplier.search_user') }}",
                    type: "GET",
                    data: {
                        'query': query,
                        'page': currentPage
                    },
                    success: function(data) {
                        $('#supplier-list').html(data.data);
                        currentPage = 1;
                        updateTableNumber();
                        updatePaginationButtons();
                    }
                });
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                if (page === undefined) {
                    return;
                }
                currentPage = parseInt(page);
                $('.pagination li').removeClass('active');
                $(this).closest('li').addClass('active');
                $.ajax({
                    url: "{{ route('supplier.search_user') }}",
                    type: "GET",
                    data: {
                        'query': $('#search').val(),
                        'page': page
                    },
                    success: function(data) {
                        $('#supplier-list').html(data.data);
                        updateTableNumber();
                        updatePaginationButtons();
                    }
                });
            });

            function updatePaginationButtons() {
                $('.pagination li:first-child').toggleClass('disabled', currentPage === 1);
                $('.pagination li:last-child').toggleClass('disabled', currentPage ===
                    {{ $suppliers->lastPage() }});
            }

            updatePaginationButtons();
        });
    </script>
@endsection
