<?php

namespace App;

use App\Exceptions\ApiErrorCustom;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Simulator extends Model
{
    /**
     * Collection with tax data
     * @var Collection
     */
    private $fees;

    /**
     * Array with filters needed for calculation
     * - valor_emprestimo (required)
     * - instituicoes (opcional)
     * - convenios (opcional)
     * - parcela (opcional)
     *
     * @var array
     */
    private $filters = [];

    public function __construct(Collection $fees, array $filters)
    {
        $this->fees = $fees;
        $this->filters = $filters;
    }

    /**
     * Performs the simulation calculation, returning its result
     *
     * @return mixed
     */
    public function executeSimulation()
    {
        $collectionReturn = [];
        $dataInstallments = $this->getInstallmentsInformation()->all();

        foreach ($dataInstallments as $val) {
            $amount = number_format($this->filters["valor_emprestimo"] * $val["coeficiente"], 2, '.', '');

            $collectionReturn[$val['instituicao']][] = [
                'taxa' => $val["taxaJuros"],
                'parcelas' => $val["parcelas"],
                'valor_parcela' => $amount,
                'convenio' => $val["convenio"]
            ];
        }

        return $collectionReturn;
    }

    /**
     * Returns rate information for installment calculation.
     * @return Collection|\Illuminate\Support\HigherOrderWhenProxy|mixed
     */
    private function getInstallmentsInformation()
    {
        $institutions = $this->filters["instituicoes"] ?? false;
        $convenants = $this->filters["convenios"] ?? false;

        $collection = $this->fees
            ->when($institutions, function ($query, $institutions) {
                return $query->whereIn('instituicao', $institutions);
            })
            ->when($convenants, function ($query, $convenants) {
                return $query->whereIn('convenio', $convenants);
            });

        $data = $collection;

        if (isset($this->filters["parcela"]) && !empty($this->filters["parcela"])) {
            $data = $collection->whereIn('parcelas', $this->filters["parcela"]);
        }

        return $data->map(function (&$data) {
            return $data;
        });
    }

}
