<?php
/**
 * Yet Another Dating Site based on socionics
 * PHP, Linux, Apache, Nginx, Twig, MongoDB, Redis, RabbitMQ, AJAX, JQuery, JSON
 *
 * @author Igor Tikhonov <itikhonov83@gmail.com>
 */

class Questionnaire {
    /**
     * @param array $answers
     * @return array
     */
    public static function getResults($answers) {
        $E = 0;
        $I = 0;
        $S = 0;
        $N = 0;
        $T = 0;
        $F = 0;
        $J = 0;
        $P = 0;

        // E I
        $answers[0] == 1 ? $E++ : $I++;
        $answers[1] == 1 ? $E++ : $I++;
        $answers[2] == 1 ? $E++ : $I++;
        $answers[3] == 1 ? $E++ : $I++;
        $answers[4] == 1 ? $E++ : $I++;
        $answers[5] == 1 ? $E++ : $I++;
        $answers[6] == 1 ? $E++ : $I++;
        $answers[7] == 1 ? $E++ : $I++;
        $answers[8] == 1 ? $E++ : $I++;

        $answers[9] == 1 ? $I++ : $E++;
        $answers[10] == 1 ? $I++ : $E++;
        $answers[11] == 1 ? $I++ : $E++;
        $answers[12] == 1 ? $I++ : $E++;
        $answers[13] == 1 ? $I++ : $E++;
        $answers[14] == 1 ? $I++ : $E++;
        $answers[15] == 1 ? $I++ : $E++;
        $answers[16] == 1 ? $I++ : $E++;
        $answers[17] == 1 ? $I++ : $E++;
        $answers[18] == 1 ? $I++ : $E++;

        // S N
        $answers[19] == 1 ? $S++ : $N++;
        $answers[20] == 1 ? $S++ : $N++;
        $answers[21] == 1 ? $S++ : $N++;
        $answers[22] == 1 ? $S++ : $N++;
        $answers[23] == 1 ? $S++ : $N++;
        $answers[24] == 1 ? $S++ : $N++;
        $answers[25] == 1 ? $S++ : $N++;
        $answers[26] == 1 ? $S++ : $N++;
        $answers[27] == 1 ? $S++ : $N++;
        $answers[28] == 1 ? $S++ : $N++;

        $answers[29] == 1 ? $N++ : $S++;
        $answers[30] == 1 ? $N++ : $S++;
        $answers[31] == 1 ? $N++ : $S++;
        $answers[32] == 1 ? $N++ : $S++;
        $answers[33] == 1 ? $N++ : $S++;
        $answers[34] == 1 ? $N++ : $S++;
        $answers[35] == 1 ? $N++ : $S++;
        $answers[36] == 1 ? $N++ : $S++;
        $answers[37] == 1 ? $N++ : $S++;
        $answers[38] == 1 ? $N++ : $S++;

        // T F
        $answers[39] == 1 ? $T++ : $F++;
        $answers[40] == 1 ? $T++ : $F++;
        $answers[41] == 1 ? $T++ : $F++;
        $answers[42] == 1 ? $T++ : $F++;
        $answers[43] == 1 ? $T++ : $F++;
        $answers[44] == 1 ? $T++ : $F++;
        $answers[45] == 1 ? $T++ : $F++;
        $answers[46] == 1 ? $T++ : $F++;
        $answers[47] == 1 ? $T++ : $F++;

        $answers[48] == 1 ? $F++ : $T++;
        $answers[49] == 1 ? $F++ : $T++;
        $answers[50] == 1 ? $F++ : $T++;
        $answers[51] == 1 ? $F++ : $T++;
        $answers[52] == 1 ? $F++ : $T++;
        $answers[53] == 1 ? $F++ : $T++;
        $answers[54] == 1 ? $F++ : $T++;
        $answers[55] == 1 ? $F++ : $T++;

        // J P
        $answers[56] == 1 ? $J++ : $P++;
        $answers[57] == 1 ? $J++ : $P++;
        $answers[58] == 1 ? $J++ : $P++;
        $answers[59] == 1 ? $J++ : $P++;
        $answers[60] == 1 ? $J++ : $P++;
        $answers[61] == 1 ? $J++ : $P++;
        $answers[62] == 1 ? $J++ : $P++;
        $answers[63] == 1 ? $J++ : $P++;
        $answers[64] == 1 ? $J++ : $P++;

        $answers[65] == 1 ? $P++ : $J++;
        $answers[66] == 1 ? $P++ : $J++;
        $answers[67] == 1 ? $P++ : $J++;
        $answers[68] == 1 ? $P++ : $J++;
        $answers[69] == 1 ? $P++ : $J++;
        $answers[70] == 1 ? $P++ : $J++;
        $answers[71] == 1 ? $P++ : $J++;
        $answers[72] == 1 ? $P++ : $J++;
        $answers[73] == 1 ? $P++ : $J++;

        if ($E > $I) {
            $pt[0][0] = 'E';
            $pt[1][0] = 'E';
            $pt[2][0] = 'E';
            $pt[3][0] = 'E';
        } else {
            $pt[0][0] = 'I';
            $pt[1][0] = 'I';
            $pt[2][0] = 'I';
            $pt[3][0] = 'I';
        }

        if ($T > $F) {
            $pt[0][2] = 'T';
            $pt[1][2] = 'T';
            $pt[2][2] = 'T';
            $pt[3][2] = 'T';
        } else {
            $pt[0][2] = 'F';
            $pt[1][2] = 'F';
            $pt[2][2] = 'F';
            $pt[3][2] = 'F';
        }

        if ($S > $N) {
            $pt[0][1] = 'S';
            $pt[1][1] = 'S';
            $pt[2][1] = 'S';
            $pt[3][1] = 'S';
        } else if ($S < $N) {
            $pt[0][1] = 'N';
            $pt[1][1] = 'N';
            $pt[2][1] = 'N';
            $pt[3][1] = 'N';
        } else {
            $pt[0][1] = 'S';
            $pt[1][1] = 'S';
            $pt[2][1] = 'N';
            $pt[3][1] = 'N';
        }

        if ($J > $P) {
            $pt[0][3] = 'J';
            $pt[1][3] = 'J';
            $pt[2][3] = 'J';
            $pt[3][3] = 'J';
        } else if ($J < $P) {
            $pt[0][3] = 'P';
            $pt[1][3] = 'P';
            $pt[2][3] = 'P';
            $pt[3][3] = 'P';
        } else {
            $pt[0][3] = 'J';
            $pt[1][3] = 'P';
            $pt[2][3] = 'J';
            $pt[3][3] = 'P';
        }

        $pt1 = [];
        for ($i = 0; $i < count($pt); $i++) {
            $pt1[$i] = $pt[$i][0] . $pt[$i][1] . $pt[$i][2] . $pt[$i][3];
        }

        $pt2 = array_unique($pt1);
        $pt3 = [];
        foreach ($pt2 as $key => $value) {
            $pt3[] = $value;
        }

        return $pt3;
    }
}