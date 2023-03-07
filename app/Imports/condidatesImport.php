<?php

namespace App\Imports;

use App\Models\Condidate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
class condidatesImport implements ToModel, WithHeadingRow , WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Condidate([
           
            'nom' => $row['nom'],
            'prenom' => $row['prenom'],
           'cin' => $row['cin'],
            'parcours' => $row['parcours'],
            'groupe' => $row['groupe'],
            'stage' => $row['stage'],
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
            '*.cin' => 'required|unique:condidates',
            'parcours' => 'required',
            '*.parcours' => 'required|in:TI,DSI,SEM,RSI,MDW',
            'groupe' => 'required',
            '*.groupe' => 'required',
            'stage' => 'required',
            '*.stage' => 'required|in:1,2',
        ];
    }
}
