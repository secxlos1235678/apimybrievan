<?php

namespace App\Http\Controllers\API\v1;

use DB;
use App\Models\OtsInArea;
use App\Models\Collateral;
use App\Models\OtsValuation;
use Illuminate\Http\Request;
use App\Models\OtsEnvironment;
use App\Models\OtsAnotherData;
use App\Models\OtsBuildingDesc;
use App\Http\Controllers\Controller;
use App\Models\OtsOtsAccordingLetterLand;
use App\Http\Requests\API\v1\Collateral\CreateOts;
use App\Http\Requests\API\v1\Collateral\CreateCollateral;

class CollateralController extends Controller
{
    /**
     * Collateral instance
     * @var \App\Models\Collateral
     */
    protected $collateral;

    /**
     * Request instance
     * @var Request
     */
    protected $request;

    /**
     * Initialize instance
     * @param Collateral $collateral
     * @param Request    $request
     */
    public function __construct(Collateral $collateral, Request $request)
    {
      $this->collateral = $collateral;
      $this->request = $request;
    }

    /**
     * Show collateral list
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      return $this->makeResponse(
        $this->collateral->withAll()->paginate($this->request->has('limit') ? $this->request->limit : 10)
      );
    }

    /**
     * Show detail collateral
     * @param  string $type
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($type, $developerId, $propertyId)
    {
      return $this->makeResponse(
        $this->collateral->withAll()->where('developer_id', $developerId)->where('property_id', $propertyId)->firstOrFail()
      );
    }

    /**
     * Store new collateral
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCollateral $request)
    {
      $data = [
        'developer_id' => $request->user()->id,
        'property_id' => $request->property_id,
        'remark' => $request->remark,
        'status' => Collateral::STATUS[0]
      ];
      $collateral = $this->collateral->create($data);
      return $this->makeResponse(
        $this->collateral->withAll()->findOrFail($collateral->id)
      );
    }

    /**
     * Update collateral
     * @return \Illuminate\Http\Response
     */
    public function update($eks, $id)
    {
      return $this->makeResponse(
        $this
          ->collateral
          ->where('status', Collateral::STATUS[0])
          ->findOrFail($id)
          ->update($this->request->only(['status', 'approved_by', 'staff_id', 'staff_name']))
          ? $this->collateral->findOrFail($id)
          : (object)[]
      );
    }

    /**
     * Store new ots collateral
     * @param  CreateOts $request
     * @param  string    $eks
     * @param  integer   $collateralId
     * @return \Illuminate\Http\Response
     */
    public function storeOts(CreateOts $request, $eks, $collateralId)
    {
      return DB::transaction(function() use($collateralId) {
        $collateral = $this->collateral->where('status', Collateral::STATUS[1])->findOrFail($collateralId);
        $collateral->otsInArea()->create($this->request->area);
        $collateral->otsLetter()->create($this->request->letter);
        $collateral->otsBuilding()->create($this->request->building);
        $collateral->otsEnvironment()->create($this->request->environment);
        $collateral->otsValuation()->create($this->request->valuation);
        $otsOther = $collateral->otsOther()->create($this->request->other);
        $otsOther->image_condition_area = $this->uploadAndGetFileNameImage($otsOther);
        $otsOther->save();
        $collateral->status = Collateral::STATUS[2];
        $collateral->save();
        return $this->makeResponse(
          $this->collateral->withAll()->find($collateralId)
        );
      });
    }

    public function getOts($eks, $collateralId)
    {
      return $this->makeResponse(
        $this->collateral->withAll()->findOrFail($collateralId)
      );
    }

    public function changeStatus($eks, $action, $collateralId)
    {
      $collateral = $this->collateral->where('status', Collateral::STATUS[2])->findOrFail($collateralId);
      $collateral->status = $action === 'approve' ? Collateral::STATUS[3] : Collateral::STATUS[4];
      $collateral->approved_by = $this->request->user()->id;
      $collateral->save();
      return $this->makeResponse(
        $this->collateral->withAll()->findOrFail($collateralId)
      );
    }

    public function disposition($eks, $collateralId)
    {
      $this->request->request->add(['status' => Collateral::STATUS[1]]);
      $this->collateral->where('status', Collateral::STATUS[0])->findOrFail($collateralId)->update($this->request->only('staff_id', 'staff_name', 'status'));
      return $this->makeResponse(
        $this->collateral->withAll()->findOrFail($collateralId)
      );
    }

    private function uploadAndGetFileNameImage($otsOther)
    {
      $image = $this->request->file('other.image_condition_area');
      $filename = $otsOther->id . '-' . time() . '.' . $image->extension();
      $path = 'collateral/ots/other';
      $image->storeAs($path, $filename);
      return url('/') . '/uploads/' . $path . '/' . $filename;
    }

    /**
     * Build response json
     * @param  mixed $data
     * @return \Illuminate\Http\Response
     */
    private function makeResponse($data)
    {
      return response()->success([
        'contents' => $data
      ]);
    }
}
