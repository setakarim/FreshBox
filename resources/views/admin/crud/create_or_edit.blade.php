@extends('layouts.admin-master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card col-12">
                <div class="card-header">
                    <h4 class="text-danger">{{ $config['title'] }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route($config['action']) }}" class="form-group" method="POST">
                        @csrf
                        @method($config['method'])
                        @isset($data)
                            <input type="hidden" name="id" value="{{ $data['id'] }}">
                        @endisset
                        <div class="col-12">
                            <div class="row mt-4">
                                @foreach($forms as $form)
                                    {{--TextArea--}}
                                    @if($form['type'] === 'textarea')
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label><b>{{ $form['label'] }}</b>@if($form['mandatory']==true)<span
                                                        style="color: red;">*</span>@endif</label>
                                                <textarea class="form-control" name="{{ $form['name'] }}"
                                                          placeholder="{{ $form['place_holder'] ? $form['place_holder'] : '' }}">{{ isset($data) ? $data[$form['name']] : old($form['name']) }}</textarea>
                                                @error($form['name'])
                                                <div class="invalid-feedback">
                                                    <p>{{ $message }}</p>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{--Text Or Number--}}
                                    @elseif($form['type'] === 'text' || $form['type'] === 'number')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <b>{{ $form['label'] }}</b>
                                                    @if($form['mandatory']==true)
                                                        <span style="color: red;">*</span>
                                                    @endif
                                                </label>
                                                <div>
                                                    <input type="{{ $form['type'] }}" name="{{ $form['name'] }}"
                                                           placeholder="{{ isset($form['place_holder']) ? $form['place_holder'] : '' }}"
                                                           class="form-control @error($form['name']) is-invalid @enderror"
                                                           value="{{ isset($data) ? $data[$form['name']] : old($form['name']) }}">
                                                    @error($form['name'])
                                                    <div class="invalid-feedback">
                                                        <p>{{ $message }}</p>
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        {{--Select Option--}}
                                    @elseif($form['type'] === 'select_option')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <b>{{ $form['label'] }}</b>
                                                    @if($form['mandatory']==true)
                                                        <span style="color: red;">*</span>
                                                    @endif
                                                </label>
                                                <select class="form-control select2" name="{{ $form['name'] }}">
                                                    <option value="">--- Please Select ---</option>
                                                    @foreach(${$form['variable']} as $row)
                                                        <option value="{{ $row[$form['option_value']] }}"
                                                                @isset($data)
                                                                @if($data[$form['name']]['id']==$row[$form['option_value']]) selected @endif
                                                            @endisset
                                                        >
                                                            {{ $row[$form['option_text']] }}</option>
                                                    @endforeach
                                                </select>
                                                @error($form['name'])
                                                <div class="invalid-feedback">
                                                    <p>{{ $message }}</p>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{--Radio Button--}}
                                    @elseif($form['type'] === 'radio')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <b>{{ $form['label'] }}</b>
                                                    @if($form['mandatory']==true)
                                                        <span style="color: red;">*</span>
                                                    @endif
                                                </label>
                                                @foreach(${$form['variable']} as $row)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                               name="{{ $form['name'] }}"
                                                               value="{{ $row['value'] }}"
                                                               id="{{ $row[$form['option_value']] }}"
                                                               @isset($data)
                                                               @if($data[$form['name']]==$row[$form['option_value']]) checked @endif
                                                            @endisset>
                                                        <label class="form-check-label">
                                                            {{ $row['label'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                                @error($form['name'])
                                                <div class="invalid-feedback">
                                                    <p>{{ $message }}</p>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{--Percentage--}}
                                    @elseif($form['type'] === 'percentage')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <b>{{ $form['label'] }}</b>
                                                    @if($form['mandatory']==true)
                                                        <span style="color: red;">*</span>
                                                    @endif
                                                </label>
                                                <div class="input-group">
                                                    <input name="{{ $form['name'] }}" type="number" step="0.01"
                                                           placeholder="{{ isset($form['place_holder']) ? $form['place_holder'] : '' }}"
                                                           class="form-control @error($form['name']) is-invalid @enderror"
                                                           value="{{ isset($data) ? $data[$form['name']] : 0.00 }}">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">%</div>
                                                    </div>
                                                </div>
                                                @error($form['name'])
                                                <div class="invalid-feedback">
                                                    <p>{{ $message }}</p>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                        {{--DatePicker--}}
                                    @elseif($form['type'] === 'datepicker')
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>
                                                    <b>{{ $form['label'] }}</b>
                                                    @if($form['mandatory']==true)
                                                        <span style="color: red;">*</span>
                                                    @endif
                                                </label>
                                                <input type="text" class="form-control datepicker"
                                                       name="{{ $form['name'] }}"
                                                       placeholder="{{ $form['place_holder'] ? $form['place_holder'] : '' }}"
                                                       value="{{ isset($data) ? $data[$form['name']] : old($form['name']) }}">
                                                @error($form['name'])
                                                <div class="invalid-feedback">
                                                    <p>{{ $message }}</p>
                                                </div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="card-body">
                                            <button class="btn btn-danger mr-1">Submit</button>
                                            <a href="{{ route($config['back-button']) }}"
                                               class="btn btn-secondary">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="https://demo.getstisla.com/assets/modules/select2/dist/css/select2.min.css" rel="stylesheet"/>
    <link href="https://demo.getstisla.com/assets/modules/bootstrap-daterangepicker/daterangepicker.css"
          rel="stylesheet"/>
@endpush

@push('js')
    <script src="https://demo.getstisla.com/assets/modules/select2/dist/js/select2.full.min.js"></script>
    <script src="https://demo.getstisla.com/assets/modules/bootstrap-daterangepicker/daterangepicker.js"></script>

    <script>
        document.addEventListener("mousewheel", function (event) {
            if (document.activeElement.type === "number") {
                document.activeElement.blur();
            }
        });
        $('.datepicker').daterangepicker({
            locale: {format: 'YYYY-MM-DD'},
            singleDatePicker: true,
        });
    </script>
@endpush
