<?php

namespace App\Imports;

use App\Models\Enseignant;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
class enseignantsImport implements ToModel, WithHeadingRow , WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Enseignant([
           
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
           'cin' => $row['cin'],
            'email' => $row['email'],
          
            //
        ]);

    }
    public function rules(): array
    {
        return [
            'nom' => 'required',
            '*.nom' => 'required',
            'prenom' => 'required',
            '*.prenom' => 'required',
               'cin' => 'required',
            '*.cin' => 'required|unique:enseignants',
            'email' => 'required',
            '*.email' => 'required|email|unique:enseignants',
        ];
    }
}
