@extends('front.layout.master')

@section('main_content')

<style>
    .container-result {
        justify-self: center;
        align-self: center;
        text-align: center;
        margin:80px;
        padding:80px;
    }
    .container-result h2 {
        font-size: 50px;
    }
    .container-result a {
        padding: 10px;
        font-weight: bold;
        margin: 0 20px;
        border-radius: 15px;
    }

    .btn-group {
        display: flex;
        justify-content: space-between;
        margin-top: 50px;
    }

    #pay-button {
        border-radius: 50px;
    }

</style>

    <div class="container-result">
        <h2 class="mb-3">Konfirmasi Pembayaran</h2>
        <p class="mb-3">Catatan: Setelah menyelesaikan pembayaran, silakan klik “Ok” untuk melanjutkan, atau tunggu beberapa saat hingga proses selesai secara otomatis.</p>
        <label>
            <input type="checkbox" id="confirm-read" /><strong>
                Harap centang kotak ini untuk melanjutkan dan mengonfirmasi bahwa Anda telah membaca catatan di atas.</strong>
          </label>
          <div class="btn-group">
              <a href="#" id="pay-button" class="btn btn-primary disabled" style="pointer-events: none;">Bayar Sekarang</a>
            </div>
    </div>

    <input type="hidden" id="order_id" value="{{ $order_id }}">

    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="SB-Mid-client-cwIBpgeY9rbFbDGI"></script>

        <script>
            console.log("Midtrans route:", "{{ route('midtrans_order') }}");
        </script>

<script>
    const snapToken = '{{ $snap_token }}';
    const checkbox = document.getElementById('confirm-read');
    const payButton = document.getElementById('pay-button');

    checkbox.addEventListener('change', function () {
        if (this.checked) {
        payButton.classList.remove('disabled');
        payButton.style.pointerEvents = 'auto';
        } else {
        payButton.classList.add('disabled');
        payButton.style.pointerEvents = 'none';
        }
    });



    document.getElementById('pay-button').addEventListener('click', function () {
        if (snapToken) {
            window.snap.pay(snapToken, {
                onSuccess: function(result){
                    fetch("{{ route('midtrans_order') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            result: result
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                    localStorage.setItem('toastr_message', data.message);
                    localStorage.setItem('toastr_type', 'success');
                    window.location.href = "{{ route('payment_succ') }}";
                    })
                    .catch(error => {
                        console.error('Payment error:', error);
                    });
                },
                onPending: function(result){
                    toastr.error(data.message);
                    window.location.href = "{{ route('home') }}";
                    console.log('pending', result);
                },
                onError: function(result){
                    toastr.error(data.message);
                    window.location.href = "{{ route('home') }}";
                    console.log('error', result);
                },
                onClose: function(){
                    alert('Payment popup closed!');
                }
            });
        } else {
            alert('Snap Token not available!');
        }
    });
</script>

@endsection
