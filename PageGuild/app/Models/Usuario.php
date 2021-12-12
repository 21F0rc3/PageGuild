<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;

    /**
     * Nome da tabela associada a essa modal
     *
     * @var string
     */
    protected $table = "usuario";

    /**
     * Primary key dessa tabela
     *
     * @var string
     */
    protected $primaryKey = "id";

    /**
     * O formato de data usado na tabela
     *
     * @var string
     */
    protected $dateFormat = "U";

    /**
     * Tabelas em que as timestamps sao guardadas
     */
    const CREATED_AT = "data_registo";
    const UPDATED_AT = "data_update";

    /**
     * Os atributos que devem permanecer escondidos
     *
     * @var array
     */
    protected $hidden = [
      "password"
    ];

    /**
     * Os atributos que poderão ser inseridos pela
     * UI da aplicação
     *
     * @var array
     */
    protected $fillable = [
        "nome", "email", "password",
        "sexo", "nif"
    ];
}