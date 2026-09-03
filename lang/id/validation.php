<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'accepted'             => ':attribute harus diterima.',
    'accepted_if'          => ':attribute harus diterima jika :other adalah :value.',
    'active_url'           => ':attribute bukan URL yang valid.',
    'after'                => ':attribute harus setelah tanggal :date.',
    'after_or_equal'       => ':attribute harus setelah atau sama dengan tanggal :date.',
    'alpha'                => ':attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':attribute hanya boleh berisi huruf, angka, dash, dan garis bawah.',
    'alpha_num'            => ':attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':attribute harus berupa array.',
    'ascii'                => ':attribute hanya boleh berisi karakter ASCII.',
    'before'               => ':attribute harus sebelum tanggal :date.',
    'before_or_equal'      => ':attribute harus sebelum atau sama dengan tanggal :date.',
    'between'              => [
        'array'   => ':attribute harus memiliki :min - :max item.',
        'file'    => ':attribute harus berukuran antara :min - :max kilobyte.',
        'numeric' => ':attribute harus antara :min - :max.',
        'string'  => ':attribute harus antara :min - :max karakter.',
    ],
    'boolean'              => ':attribute harus bernilai benar atau salah.',
    'confirmed'            => ':attribute konfirmasi tidak cocok.',
    'current_password'     => 'Password salah.',
    'date'                 => ':attribute bukan tanggal yang valid.',
    'date_equals'          => ':attribute harus sama dengan tanggal :date.',
    'date_format'          => ':attribute tidak cocok dengan format :format.',
    'declined'             => ':attribute harus ditolak.',
    'declined_if'          => ':attribute harus ditolak jika :other adalah :value.',
    'different'            => ':attribute dan :other harus berbeda.',
    'digits'               => ':attribute harus berisi :digits digit.',
    'digits_between'       => ':attribute harus berisi antara :min - :max digit.',
    'dimensions'           => ':attribute memiliki dimensi gambar yang tidak valid.',
    'distinct'             => ':attribute memiliki nilai duplikat.',
    'doesnt_end_with'      => ':attribute tidak boleh diakhiri dengan salah satu dari: :values',
    'doesnt_start_with'    => ':attribute tidak boleh diawali dengan salah satu dari: :values',
    'email'                => ':attribute harus berupa alamat email yang valid.',
    'ends_with'            => ':attribute harus diakhiri dengan salah satu dari: :values',
    'enum'                 => ':attribute yang dipilih tidak valid.',
    'exists'               => ':attribute yang dipilih tidak valid.',
    'file'                 => ':attribute harus berupa berkas.',
    'filled'               => ':attribute wajib diisi.',
    'gt'                   => [
        'array'   => ':attribute harus memiliki lebih dari :value item.',
        'file'    => ':attribute harus berukuran lebih dari :value kilobyte.',
        'numeric' => ':attribute harus lebih besar dari :value.',
        'string'  => ':attribute harus lebih panjang dari :value karakter.',
    ],
    'gte'                  => [
        'array'   => ':attribute harus memiliki :value item atau lebih.',
        'file'    => ':attribute harus berukuran :value kilobyte atau lebih.',
        'numeric' => ':attribute harus lebih besar dari atau sama dengan :value.',
        'string'  => ':attribute harus lebih panjang dari atau sama dengan :value karakter.',
    ],
    'image'                => ':attribute harus berupa gambar.',
    'in'                   => ':attribute yang dipilih tidak valid.',
    'in_array'             => ':attribute tidak ada di :other.',
    'integer'              => ':attribute harus berupa bilangan bulat.',
    'ip'                   => ':attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':attribute harus berupa string JSON yang valid.',
    'lt'                   => [
        'array'   => ':attribute harus memiliki kurang dari :value item.',
        'file'    => ':attribute harus berukuran kurang dari :value kilobyte.',
        'numeric' => ':attribute harus kurang dari :value.',
        'string'  => ':attribute harus lebih pendek dari :value karakter.',
    ],
    'lte'                  => [
        'array'   => ':attribute harus memiliki :value item atau kurang.',
        'file'    => ':attribute harus berukuran :value kilobyte atau kurang.',
        'numeric' => ':attribute harus kurang dari atau sama dengan :value.',
        'string'  => ':attribute harus lebih pendek dari atau sama dengan :value karakter.',
    ],
    'max'                  => [
        'array'   => ':attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => ':attribute tidak boleh berukuran lebih dari :max kilobyte.',
        'numeric' => ':attribute tidak boleh lebih besar dari :max.',
        'string'  => ':attribute tidak boleh lebih panjang dari :max karakter.',
    ],
    'mimes'                => ':attribute harus berupa tipe berkas: :values.',
    'mimetypes'            => ':attribute harus berupa tipe berkas: :values.',
    'min'                  => [
        'array'   => ':attribute harus memiliki setidaknya :min item.',
        'file'    => ':attribute harus berukuran setidaknya :min kilobyte.',
        'numeric' => ':attribute harus setidaknya :min.',
        'string'  => ':attribute harus setidaknya :min karakter.',
    ],
    'not_in'               => ':attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':attribute harus berupa angka.',
    'password'             => 'Password salah.',
    'present'              => ':attribute harus ada.',
    'prohibited'           => ':attribute dilarang.',
    'prohibited_if'        => ':attribute dilarang jika :other adalah :value.',
    'prohibited_unless'    => ':attribute dilarang kecuali :other ada di :values.',
    'prohibits'            => ':attribute melarang :other ada.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':attribute wajib diisi.',
    'required_if'          => ':attribute wajib diisi jika :other adalah :value.',
    'required_unless'      => ':attribute wajib diisi kecuali :other ada di :values.',
    'required_with'        => ':attribute wajib diisi ketika :values ada.',
    'required_with_all'    => ':attribute wajib diisi ketika :values ada.',
    'required_without'     => ':attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':attribute wajib diisi ketika tidak ada :values yang ada.',
    'same'                 => ':attribute dan :other harus cocok.',
    'size'                 => [
        'array'   => ':attribute harus memiliki :size item.',
        'file'    => ':attribute harus berukuran :size kilobyte.',
        'numeric' => ':attribute harus bernilai :size.',
        'string'  => ':attribute harus :size karakter.',
    ],
    'starts_with'          => ':attribute harus diawali dengan salah satu dari: :values',
    'string'               => ':attribute harus berupa string.',
    'timezone'             => ':attribute harus berupa zona waktu yang valid.',
    'unique'               => ':attribute sudah digunakan.',
    'uploaded'             => ':attribute gagal diunggah.',
    'url'                  => 'Format :attribute tidak valid.',
    'uuid'                 => ':attribute harus berupa UUID yang valid.',
    'ulid'                 => ':attribute harus berupa ULID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'attributes' => [
        'name'                  => 'Nama',
        'email'                 => 'Email',
        'password'              => 'Password',
        'password_confirmation' => 'Konfirmasi Password',
        'role'                  => 'Role',
        'title'                 => 'Judul',
        'short_description'     => 'Deskripsi Singkat',
        'description'           => 'Deskripsi',
        'whatsapp'              => 'No WhatsApp',
        'contact_email'         => 'Email Kontak',
        'address'               => 'Alamat',
        'gmail_access'          => 'Gmail Akses',
        'category'              => 'Kategori',
        'company_id'            => 'Perusahaan',
        'registration_end'      => 'Tanggal Tutup Pendaftaran',
        'start_date'            => 'Tanggal Mulai',
        'end_date'              => 'Tanggal Selesai',
        'work_type'             => 'Tipe Karya',
        'category'              => 'Kategori',
        'file'                  => 'Berkas',
        'photo'                 => 'Foto',
        'logo'                  => 'Logo',
        'slug'                  => 'Slug',
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    */

];
