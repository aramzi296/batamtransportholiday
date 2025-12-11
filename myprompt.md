Step #1:
pada tabel vehicles tambahkan kolom price_per_day_no_driver (number) dan machine_cc(number).


Step #2:
ubah form pada http://127.0.0.1:8000/admin/vehicles/create yang digunakan untuk create dan edit dengan perubahaan sebagai berikut:
1. hapus section Kategori Harga Sewa.
2. tambahkan input field: name, price_per_day, price_per_day_no_driver dan machine_cc.
3. pesan error validasi pada field terkait, ditampilkan dalam bahasa indonesia. 
4. gunakan sweet alert untuk menampilkan alert error atau sukses.
5. tambahkan field member_id dengan label Pemilik. input berupa dropdown dari tabel user dengan kategori member. tambahkan button untuk menambah user, jika user belum ada di sistem.

Step #3:
menyambung step #2. tambahkan field feature pada form.


step #4:
buatkan seeder 25 kendaraan dummy untuk mengisi tabel vehicles. pilih foto kendaraan secara acak pada foto yang ada di folder public, subfolder kendaraan

step #5:
perbaiki halaman http://127.0.0.1:8000/vehicles dengan menampilkan kendaraan sesuai data yang ada pada daftar kendaran yang aktif di tabel vehicles.


step #6:
pada halaman http://127.0.0.1:8000/vehicles. tambah input pada filter yaitu jumlah seats pada dengan label Max Penumpang. buat berupa dropdown sebagai hasil dari grouping data pada kolom seats pada tabel vehicles. jika user pilih seats tersebut, maka difilter semua record dengan nilai seats <= pilihan user.


step #7:
pada halaman http://127.0.0.1:8000/vehicles. ubah urutan filter:
1. Dengan supir
2. Max Penumpang
3. Ketegori
4. Merek
5. Urutkan

step #8:
http://127.0.0.1:8000/booking?vehicle_id=15&with_driver=1
ketika user klik tombol booking maka ambil data maximum tiga kendaraan kendaraan dari tabel vehicles sebagai kendaraan alternatif  dengan kriteria:
1. category_id sama dengan yang dipilih user
2. seats sama dengan yang dipilih user
3. jika user pilih price_per_day, price_per_day sama dengan yang dipilih user
4. jika user pilih price_per_day_no_driver, maka price_per_day_no_driver sama dengan yang dipilih user.
5. nomor id bukan yang sudah dipilih oleh user.
6. diurut berdasarkan queue_number

jika kendaraan alternatif = 0, maka tampilkan satu pilihan saja, yaitu kendaraan yang dipilih oleh user, untuk proses booking selanjutnya.

jika kendaraan alternatif lebih dari 0, maka bandingkan nilai queue_number kendaraan yang dipilih user dengan   kendaraan alternatif. jika queue_number kendaraan yang dipilih oleh user paling kecil, maka tampilkan satu pilihan saja, yaitu kendaraan yang dipilih oleh user, untuk proses booking selanjutnya.

jika queue_number kendaraan yang dipilih oleh user tidak paling kecil, maka tambahkan pilihan kendaraan dengan kendaraan alternatif supaya user bisa memilih lagi dan lanjutkan proses booking. default pilihan ada pada kendaran dengan nomor queue_number paling kecil.


Step #9:
http://127.0.0.1:8000/booking?vehicle_id=14&with_driver=0
tambahkan step 1 pada step booking sekarang sehingg step booking menjadi:
1. pilih kendaraan. tampilan pilihan kendaraan baik satu atau lebih kendaraan adalah dalam bentuk baris. kolomnya: radio button, thumbnail, brand, model, seats, harga yang dipilih
2. pilih tanggal dan lama pemakaian.
3. informasi penyewa.  
4. detil booking

Step #10:
    $curl = curl_init();
    curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://app.saungwa.com/api/create-message',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array(
    'appkey' => '7d389aad-ba64-4330-bde9-79aac1c52b48',
    'authkey' => 'lX0GKhWw3rCJBcErpWRpQZTfz5IszhomAMm5o8dxRZ6qMfcMh6',
    'to' => '628117007201',
    'message' => 'Halo, saya ingin bertanya tentang kendaraan. test wa berjalan dengan sukses',
    'sandbox' => 'false'
    ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;

buatkan saya fungsi atau api yang mengirimkan pesan whatsapp sesuai kode yang diberikan ini. fungsi/api ini jika dipanggil dengan kode booking. fungsi tersebut akan mengambil informasi dari database, membuat pesan dan mengirimkan pesan tersebut ke nomor yang diberikan.



step #11:
ketika saya deploy ke server, saya dapat pesan error pada console:
GET https://bootstrap.sebatam.com/livewire/livewire.js?id=646f9d24 net::ERR_ABORTED 404 (Not Found)


step #12:
https://bootstrap.sebatam.com/booking?vehicle_id=13&with_driver=1
saya ingin ketika booking dikirim, informasi booking juga dikirim ke beberapa nomor admin.


step #13:
saya ingin mendefinisikan nomor email admin. email bisa lebih darisatu. semua notifikasi booking dan kontak form akan dikirimkan ke email ini.
setelah didefinisikan, perbarui email tujuan dari kontak form ke email ini.
saya juga ingin kontak form juga dikirimkan ke nomor whatsapp admin.


http://127.0.0.1:8000/admin/bookings/1 tambahkan fitur untuk mengubah harga perhari untuk booking ini


saya ingin mencatat setiap history event setiap booking. mulai dari booking dikirim oleh customer, email notification dikirim ke customer dan admin, notification whatsapp dikirim ke admin, admin konfirmasi booking, admin kirim email konfirmasi ke customer, admin kirim whatsapp notification ke customer.
notifikasi booking yang dikirim oleh customer dibuat secara otomatis ketika customer kirim booking. email dari admin ke customer dilakukan secara manual dengan klik button yang disediakan pada halaman suatu booking.

setiap notifikasi booking ke admin yang dikirim dengan whatsapp, tambahkan link ke halaman booking supaya admin bisa buka detail booking tersebut.
[todo] link belum berhasil terkirim. masih plain text





Step #xx:
hapus tabel dan model rental_categories dan vehicle_rental_categories karena tidak diperlukan lagi. 












