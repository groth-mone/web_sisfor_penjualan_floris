<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<title>Invoice Penjualan | {{ $data[0]->kode_penjualan ?? '-' }}</title>
	<style>
		body {
			font-family: DejaVu Sans, sans-serif;
			font-size: 11px;
			color: #222;
		}
		.page {
			padding: 18px;
		}
		.heading-table,
		.info-table,
		.item-table,
		.payment-table,
		.summary-table {
			width: 100%;
			border-collapse: collapse;
		}
		.heading-table td {
			vertical-align: top;
		}
		.brand {
			font-size: 24px;
			font-weight: bold;
			letter-spacing: 1px;
		}
		.brand-subtitle {
			font-size: 12px;
			font-weight: bold;
			margin-top: 4px;
		}
		.text-right {
			text-align: right;
		}
		.muted {
			color: #666;
		}
		.section-title {
			margin: 18px 0 8px;
			padding: 6px 10px;
			background: #f3f3f3;
			font-size: 12px;
			font-weight: bold;
			border: 1px solid #ddd;
		}
		.info-table td {
			padding: 4px 0;
			vertical-align: top;
		}
		.info-label {
			width: 120px;
			font-weight: bold;
		}
		.item-table th,
		.item-table td,
		.payment-table th,
		.payment-table td {
			border: 1px solid #d8d8d8;
			padding: 6px 8px;
		}
		.item-table th,
		.payment-table th {
			background: #f7f7f7;
			font-weight: bold;
			text-align: left;
		}
		.summary-table td {
			padding: 4px 0;
		}
		.summary-label {
			width: 160px;
			font-weight: bold;
		}
		.total-row td {
			font-size: 13px;
			font-weight: bold;
			padding-top: 8px;
			border-top: 1px solid #999;
		}
		.preview-image {
			width: 150px;
			height: 150px;
			object-fit: cover;
			border: 1px solid #ccc;
			padding: 3px;
		}
		.note-box {
			border: 1px solid #d8d8d8;
			padding: 10px;
			min-height: 44px;
		}
		.footer-note {
			margin-top: 26px;
			font-size: 10px;
			color: #666;
		}
	</style>
</head>
<body>
@php
	$terbilang = function ($angka, $rupiah = false) use (&$terbilang) {
		$angka = abs((int) $angka);
		$huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
		$temp = '';

		if ($angka < 12) {
			$temp = $huruf[$angka];
		} elseif ($angka < 20) {
			$temp = $huruf[$angka - 10] . ' belas';
		} elseif ($angka < 100) {
			$temp = $huruf[(int) floor($angka / 10)] . ' puluh' . ($angka % 10 > 0 ? ' ' . $terbilang($angka % 10) : '');
		} elseif ($angka < 200) {
			$temp = 'seratus' . ($angka % 100 > 0 ? ' ' . $terbilang($angka - 100) : '');
		} elseif ($angka < 1000) {
			$temp = $huruf[(int) floor($angka / 100)] . ' ratus' . ($angka % 100 > 0 ? ' ' . $terbilang($angka % 100) : '');
		} elseif ($angka < 1000000) {
			$temp = $terbilang((int) floor($angka / 1000)) . ' ribu' . ($angka % 1000 > 0 ? ' ' . $terbilang($angka % 1000) : '');
		} elseif ($angka < 1000000000) {
			$temp = $terbilang((int) floor($angka / 1000000)) . ' juta' . ($angka % 1000000 > 0 ? ' ' . $terbilang($angka % 1000000) : '');
		} elseif ($angka < 1000000000000) {
			$temp = $terbilang((int) floor($angka / 1000000000)) . ' milyar' . ($angka % 1000000000 > 0 ? ' ' . $terbilang($angka % 1000000000) : '');
		} else {
			$temp = $terbilang((int) floor($angka / 1000000000000)) . ' triliun' . ($angka % 1000000000000 > 0 ? ' ' . $terbilang($angka % 1000000000000) : '');
		}

		$temp = strtoupper(trim(preg_replace('/\s+/', ' ', $temp)));
		return $rupiah ? $temp . ' RUPIAH' : $temp;
	};
@endphp

@foreach($data as $dt)
@php
	$subtotal = (int) ($dt->total_penjualan ?? 0);
	$ongkir = (int) ($dt->ongkir ?? 0);
	$totalTagihan = $subtotal + $ongkir;
	$totalPembayaran = (int) ($dt->total_pembayaran ?? 0);
	$sisaTagihan = $totalTagihan - $totalPembayaran;
	$fotoPesananPath = !empty($dt->foto_pesanan) ? public_path('foto_pesanan/' . $dt->foto_pesanan) : null;
	$fotoPesananSrc = $fotoPesananPath ? 'file:///' . str_replace('\\', '/', $fotoPesananPath) : null;
@endphp
<div class="page">
	<table class="heading-table">
		<tr>
			<td>
				<div class="brand">ADELIA FLORIST</div>
				<div class="brand-subtitle">Invoice Penjualan Florist</div>
				<div class="muted">
					Jl. Yos Sudarso No. 23B Brebes<br>
					Jl. K.H Zainal Abidin, Kademangan, Dukuhturi, Tegal<br>
					WhatsApp: 0877 8645 0085 / 0823 1117 1971
				</div>
			</td>
			<td class="text-right">
				<div style="font-size: 18px; font-weight: bold;">{{ $dt->kode_penjualan }}</div>
				<div class="muted">Tanggal: {{ \Carbon\Carbon::parse($dt->tanggal_penjualan)->format('d-m-Y') }}</div>
				<div class="muted">Status Penjualan: {{ $dt->status_penjualan ?? '-' }}</div>
				<div class="muted">Status Pengiriman: {{ $dt->status_pengiriman ?? '-' }}</div>
			</td>
		</tr>
	</table>

	<div class="section-title">Informasi Pelanggan</div>
	<table class="info-table">
		<tr>
			<td class="info-label">Nama Pelanggan</td>
			<td>: {{ $dt->pelanggan ?? '-' }}</td>
		</tr>
		<tr>
			<td class="info-label">Nomor Pelanggan</td>
			<td>: {{ $dt->nomor_pelanggan ?? '-' }}</td>
		</tr>
		<tr>
			<td class="info-label">Alamat Pengiriman</td>
			<td>: {{ $dt->alamat_pengiriman ?: '-' }}</td>
		</tr>
		<tr>
			<td class="info-label">Keterangan</td>
			<td>: {{ $dt->keterangan_penjualan ?: '-' }}</td>
		</tr>
	</table>

	<div class="section-title">Detail Item</div>
	<table class="item-table">
		<thead>
			<tr>
				<th style="width: 35px;">No</th>
				<th style="width: 90px;">Kode</th>
				<th>Nama Item</th>
				<th style="width: 70px;">Satuan</th>
				<th style="width: 65px;">Qty</th>
				<th style="width: 105px;">Harga</th>
				<th style="width: 110px;">Subtotal</th>
			</tr>
		</thead>
		<tbody>
			@forelse($detail as $det)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ $det->kode_barang }}</td>
				<td>{{ $det->nama_barang }}</td>
				<td>{{ $det->satuan_barang }}</td>
				<td>{{ $det->jml_penjualan }}</td>
				<td>Rp {{ number_format($det->harga_penjualan, 0, ',', '.') }}</td>
				<td>Rp {{ number_format($det->harga_penjualan * $det->jml_penjualan, 0, ',', '.') }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="7" class="text-right">Tidak ada detail item.</td>
			</tr>
			@endforelse
		</tbody>
	</table>

	<div class="section-title">Ringkasan Tagihan</div>
	<table class="summary-table">
		<tr>
			<td class="summary-label">Subtotal Item</td>
			<td>: Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
		</tr>
		<tr>
			<td class="summary-label">Ongkir</td>
			<td>: Rp {{ number_format($ongkir, 0, ',', '.') }}</td>
		</tr>
		<tr class="total-row">
			<td class="summary-label">Total Tagihan</td>
			<td>: Rp {{ number_format($totalTagihan, 0, ',', '.') }}</td>
		</tr>
		<tr>
			<td class="summary-label">Terbilang</td>
			<td>: {{ $terbilang($totalTagihan, true) }}</td>
		</tr>
	</table>

	<div class="section-title">Riwayat Pembayaran</div>
	<table class="payment-table">
		<thead>
			<tr>
				<th style="width: 35px;">No</th>
				<th>Tanggal</th>
				<th>Metode</th>
				<th>Detail</th>
				<th style="width: 120px;">Nominal</th>
			</tr>
		</thead>
		<tbody>
			@forelse($pembayaran as $pay)
			<tr>
				<td>{{ $loop->iteration }}</td>
				<td>{{ \Carbon\Carbon::parse($pay->tanggal_pembayaran)->format('d-m-Y') }}</td>
				<td>{{ $pay->metode_pembayaran }}</td>
				<td>{{ $pay->metode_detail ?: '-' }}</td>
				<td>Rp {{ number_format($pay->nominal_pembayaran, 0, ',', '.') }}</td>
			</tr>
			@empty
			<tr>
				<td colspan="5" class="text-right">Belum ada pembayaran.</td>
			</tr>
			@endforelse
		</tbody>
	</table>

	<table class="summary-table" style="margin-top: 8px;">
		<tr>
			<td class="summary-label">Total Pembayaran</td>
			<td>: Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</td>
		</tr>
		<tr>
			<td class="summary-label">Sisa Tagihan</td>
			<td>: Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</td>
		</tr>
	</table>

	<div class="section-title">Catatan Kuitansi</div>
	<div class="note-box">{{ $dt->catatan_kuitansi ?: '-' }}</div>

	@if($fotoPesananPath && file_exists($fotoPesananPath))
	<div class="section-title">Preview Pesanan</div>
	<img src="{{ $fotoPesananSrc }}" alt="Foto Pesanan" class="preview-image">
	@endif

	<div class="footer-note">
		Invoice ini dibuat otomatis oleh sistem penjualan florist. Simpan dokumen ini sebagai bukti transaksi.
	</div>
</div>
@endforeach
</body>
</html>
