ini_set('max_execution_time', 300);

        $img = Image::canvas(800, 600, '#EEEEE');
        $img2 = Image::make(Storage::get(auth()->user()->avatar));

        $height = $img2->height();
        $width = $img2->width();

        $ratio = $height / $width;

        if ($ratio > 1.10) {
            // height based
            if ($height > $width) {
                $img2->resize(null, 550, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        if ($ratio >= .9 && $ratio <= 1.10) {
            // height based
            $img2->resize(null, 550, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        if ($ratio <= .75) {
            // width based
            if ($width > $height) {
                $img2->resize(750, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
        }

        // width based
        if ($height == $width) {
            $img2->resize(550, 550, function ($constraint) {
                $constraint->aspectRatio();
            });
        }


        $img3 = Image::canvas($img2->width()-6, $img2->height(), '#666666')->blur(100)->opacity(45);

        $marginTop = ((600 - $img2->height()) / 2) + 3;

        echo '<img src="'.$img->insert($img3, 'top', 0, $marginTop)->insert($img2, 'center')->encode('data-url').'">';



        // echo '<img src="'.$img->insert($img3, 'top', 0, 30)->encode('data-url').'">';

