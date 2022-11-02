<?php

namespace Pimeo\Http\Controllers\Pim;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kris\LaravelFormBuilder\FormBuilder;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Pimeo\Forms\TechnicalBulletinCreateForm;
use Pimeo\Forms\TechnicalBulletinEditForm;
use Pimeo\Forms\TechnicalBulletinShowForm;
use Pimeo\Http\Controllers\Controller;
use Pimeo\Http\Requests\Pim\TechnicalBulletin\AttributeUpdateRequest;
use Pimeo\Http\Requests\Pim\TechnicalBulletin\CreateRequest;
use Pimeo\Http\Requests\Pim\TechnicalBulletin\DeleteRequest;
use Pimeo\Http\Requests\Pim\TechnicalBulletin\EditRequest;
use Pimeo\Http\Requests\Pim\TechnicalBulletin\StoreRequest;
use Pimeo\Http\Requests\Pim\TechnicalBulletin\UpdateRequest;
use Pimeo\Jobs\Pim\TechnicalBulletin\CreateTechnicalBulletin;
use Pimeo\Jobs\Pim\TechnicalBulletin\DeleteTechnicalBulletin;
use Pimeo\Jobs\Pim\TechnicalBulletin\UpdateTechnicalBulletin;
use Pimeo\Models\AttributableModelStatus;
use Pimeo\Models\Attribute;
use Pimeo\Models\LinkAttribute;
use Pimeo\Models\TechnicalBulletin;
use Pimeo\Repositories\AttributeRepository;
use Pimeo\Repositories\TechnicalBulletinRepository;
use Pimeo\Services\InlineAttributeEditionTrait;
use Pimeo\Services\ModelListing;

class TechnicalBulletinController extends Controller
{
    use FormBuilderTrait, ModelListing, InlineAttributeEditionTrait;

    /**
     * @var TechnicalBulletinRepository
     */
    protected $technicalBulletins;

    /**
     * @param AttributeRepository $attributes
     * @param TechnicalBulletinRepository $technicalBulletins
     */
    public function __construct(AttributeRepository $attributes, TechnicalBulletinRepository $technicalBulletins)
    {
        $this->attributes = $attributes;
        $this->technicalBulletins = $technicalBulletins;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param string $status
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $status = AttributableModelStatus::COMPLETE_STATUS)
    {
        $list = $this->listAll(
            'bulletins',
            $request,
            'technical-bulletin.index',
            TechnicalBulletinRepository::class,
            $status
        );

        $this->breadcrumb('technical-bulletin');

        return view('pim.technical-bulletins.index')
            ->with($list)
            ->withActiveStatus($status)
        ;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param CreateRequest $request
     * @param FormBuilder   $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function create(CreateRequest $request, FormBuilder $formBuilder)
    {
        $attributes = $this->attributes->allRequired(Attribute::MODEL_TYPE_TECHNICAL_BULLETIN);

        $form = $formBuilder->create(TechnicalBulletinCreateForm::class, [
            'method' => 'POST',
            'url'    => route('technical-bulletin.store'),
        ], ['attributes' => $attributes]);

        $this->breadcrumb(
            'technical-bulletin',
            trans('breadcrumb.technical-bulletins.create'),
            'technical-bulletin.create'
        );

        return view('pim.technical-bulletins.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request Bulletin create request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $this->dispatch(new CreateTechnicalBulletin($request->all()));

        flash()->success(trans('technical-bulletin.create.saved'), true);

        return redirect()->route('technical-bulletin.index');
    }

    public function show(TechnicalBulletin $technical_bulletin, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(TechnicalBulletinShowForm::class, [
            'readonly' => true,
        ], [
            'attributes'         => $technical_bulletin->linkAttributes,
            'medias'             => $technical_bulletin->mediaLinks,
            'technical_bulletin' => $technical_bulletin,
        ]);

        $this->breadcrumb(
            'technical-bulletin',
            trans('breadcrumb.technical-bulletins.show'),
            ['technical-bulletin.show', $technical_bulletin->id]
        );

        return view('pim.technical-bulletins.show', compact('form', 'technical_bulletin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param EditRequest       $request
     * @param TechnicalBulletin $technical_bulletin
     * @param FormBuilder       $formBuilder
     * @return \Illuminate\Http\Response
     */
    public function edit(EditRequest $request, TechnicalBulletin $technical_bulletin, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(TechnicalBulletinEditForm::class, [
            'method' => 'PUT',
            'url'    => route('technical-bulletin.update', $technical_bulletin->id),
        ], [
            'required_attributes' => $this->attributes->allRequired(Attribute::MODEL_TYPE_TECHNICAL_BULLETIN),
            'attributes'          => $technical_bulletin->linkAttributes,
            'medias'              => $technical_bulletin->mediaLinks,
            'technical_bulletin'  => $technical_bulletin,
        ]);

        $attributes = $this->attributes->allNotLinkedToProduct(
            $technical_bulletin,
            null,
            Attribute::MODEL_TYPE_TECHNICAL_BULLETIN
        );

        $this->breadcrumb(
            'technical-bulletin',
            trans('breadcrumb.technical-bulletins.edit'),
            'technical-bulletin.edit'
        );

        return view('pim.technical-bulletins.edit', compact('form', 'technical_bulletin', 'attributes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request Bulletin update request
     * @param TechnicalBulletin $technical_bulletin
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, TechnicalBulletin $technical_bulletin)
    {
        $this->dispatch(new UpdateTechnicalBulletin($technical_bulletin, $request->all()));

        flash()->success(trans('technical-bulletin.edit.saved'), true);

        return redirect()->route('technical-bulletin.index');
    }

    /**
     * Show the form for editing the specified attribute.
     *
     * @param EditRequest        $request
     * @param  TechnicalBulletin $technical_bulletin
     * @return \Illuminate\Http\Response
     */
    public function editAttribute(EditRequest $request, TechnicalBulletin $technical_bulletin)
    {
        $attributesList = $this->attributes->all(
            Auth::user()->getCompany()->id,
            Attribute::MODEL_TYPE_TECHNICAL_BULLETIN
        );
        $attributes = [];
        $bulletinAttributesIDs = $technical_bulletin->linkAttributes->pluck('attribute_id')->toArray();
        $language_code = Auth::user()->getLanguageCode();

        foreach ($attributesList as $attribute) {
            // Ne pas afficher les attributs déjà associés au produit
            $alreadyInBulletin = (in_array($attribute->id, $bulletinAttributesIDs));
            if (!$alreadyInBulletin) {
                $attributes['choices'][] = [
                    'id'   => $attribute->id,
                    'text' => $attribute->label->values[$language_code],
                ];
            }
        }

        $this->breadcrumb(
            'technical-bulletin',
            trans('breadcrumb.technical-bulletins.edit-attribute'),
            'technical-bulletin.edit-attribute'
        );

        return view('pim.technical-bulletins.edit-attribute', compact('attributes', 'technical_bulletin'));
    }

    /**
     * Update the specified attribute in storage.
     *
     * @param  AttributeUpdateRequest $request Bulletin attribute update request
     * @param  TechnicalBulletin $technical_bulletin
     * @return \Illuminate\Http\Response
     */
    public function updateAttribute(AttributeUpdateRequest $request, TechnicalBulletin $technical_bulletin)
    {
        if ($request->input('attributesToDelete') != null) {
            $attributesToDelete = explode(',', $request->input('attributesToDelete'));
            LinkAttribute::destroy($attributesToDelete);
        }

        // Save new empty LinkAttributes
        $uniqueNewAttributes = array_unique((array)$request->input('link_attribute'));
        foreach ($uniqueNewAttributes as $newAttributeID) {
            if (!$technical_bulletin->hasLinkAttribute($newAttributeID)) {
                $linkAttribute = new LinkAttribute(['attribute_id' => $newAttributeID]);
                $technical_bulletin->linkAttributes()->save($linkAttribute);
            }
        }

        $technical_bulletin->status = AttributableModelStatus::INCOMPLETE_STATUS;

        flash()->success(trans('technical-bulletin.edit-attribute.saved'), true);

        return redirect()->route('technical-bulletin.edit', $technical_bulletin->id);
    }

    public function bulletinAddInlineAttribute(
        Request $request,
        FormBuilder $formBuilder,
        TechnicalBulletin $technical_bulletin
    ) {
        return $this->addInlineAttribute(
            $request,
            $formBuilder,
            TechnicalBulletinRepository::class,
            $technical_bulletin,
            Attribute::MODEL_TYPE_TECHNICAL_BULLETIN
        );
    }

    public function bulletinDeleteInlineAttribute(Request $request, TechnicalBulletin $technical_bulletin)
    {
        return $this->deleteInlineAttribute(
            $request,
            TechnicalBulletinRepository::class,
            $technical_bulletin,
            Attribute::MODEL_TYPE_TECHNICAL_BULLETIN
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  DeleteRequest $request Bulletin delete request
     * @param  TechnicalBulletin $technical_bulletin
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteRequest $request, TechnicalBulletin $technical_bulletin)
    {
        $this->dispatch(new DeleteTechnicalBulletin($technical_bulletin));

        flash()->success(trans('technical-bulletin.deleted'), true);

        return redirect()->route('technical-bulletin.index');
    }
}
