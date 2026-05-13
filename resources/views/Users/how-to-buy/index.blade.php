@extends('Users.Template.index')

@section('title', 'How To Buy')

@push('css')
    <style>
        .progress-wrapper {
            padding: 120px 20px 80px;
            max-width: 1100px;
            margin: auto;
        }

        /* HEADER */
        .progress-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .progress-header h2 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #111;
        }

        .progress-header p {
            color: #6b7280;
            max-width: 650px;
            margin: auto;
            line-height: 1.8;
        }

        /* CONTAINER */
        .stepper-container {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 40px;
        }

        /* CENTER LINE */
        .stepper-container::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 3px;
            height: 100%;
            background: linear-gradient(to bottom,
                    #e5e7eb,
                    #d1d5db);
            border-radius: 20px;
        }

        /* STEP ITEM */
        .step-item {
            display: flex;
            align-items: center;
            position: relative;
            width: 100%;
        }

        .step-item:nth-child(odd) {
            justify-content: flex-start;
        }

        .step-item:nth-child(even) {
            justify-content: flex-end;
        }

        /* CARD */
        .step-content {
            width: 42%;
            background: #ffffff;
            padding: 22px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
            position: relative;
            transition: .3s ease;
            border-top: 4px solid #111111;
        }

        .step-content:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.08);
        }

        /* connector */
        .step-item:nth-child(odd) .step-content::after {
            content: "";
            position: absolute;
            right: -30px;
            top: 50%;
            width: 30px;
            height: 2px;
            background: #d1d5db;
        }

        .step-item:nth-child(even) .step-content::after {
            content: "";
            position: absolute;
            left: -30px;
            top: 50%;
            width: 30px;
            height: 2px;
            background: #d1d5db;
        }

        /* NUMBER */
        .step-circle {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #111111;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            border: 4px solid white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .step-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 8px;
            color: #111;
        }

        .step-description {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.6;
        }

        /* MOBILE */
        @media(max-width:768px) {
            .progress-wrapper {
                padding: 100px 15px 60px;
            }

            .progress-header h2 {
                font-size: 24px;
            }

            .stepper-container::before {
                left: 30px;
            }

            .step-item {
                justify-content: flex-start !important;
                padding-left: 70px;
            }

            .step-circle {
                left: 30px;
                transform: none;
                width: 45px;
                height: 45px;
                font-size: 16px;
            }

            .step-content {
                width: 100%;
                padding: 18px;
            }

            .step-content::after {
                display: none;
            }
        }
    </style>
@endpush

@section('content')
    <section class="progress-wrapper">

        <div class="progress-header">
            <h2>How to Buy Our Products</h2>

            <p>
                Shopping with us is quick and easy.
                Follow these simple steps to place your order
                and receive your products without any hassle.
            </p>
        </div>

        <div class="stepper-container">
            @foreach ($steps as $step)
                <div class="step-item">

                    {{-- Step Number --}}
                    <div class="step-circle">
                        {{ $step->step_order }}
                    </div>

                    {{-- Content --}}
                    <div class="step-content">
                        <div class="step-title">
                            {{ $step->title }}
                        </div>

                        @if ($step->description)
                            <div class="step-description">
                                {{ $step->description }}
                            </div>
                        @endif
                    </div>

                </div>
            @endforeach
        </div>

    </section>
@endsection

@push('scripts')
@endpush
