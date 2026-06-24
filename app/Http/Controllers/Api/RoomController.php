<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // ----------------------------------------------------------------
    // Shared: build a room array from an already-loaded Room model.
    // Assumes 'beds' relation is already loaded to avoid N+1.
    // ----------------------------------------------------------------
    private function roomResource(Room $room, bool $includeBeds = false): array
    {
        $beds         = $room->beds;
        $occupiedBeds = $beds->where('is_occupied', true);
        $availableBeds = $beds->where('is_occupied', false);

        $data = [
            'id'              => $room->id,
            'room_number'     => $room->room_number,
            'type'            => $room->type,
            'status'          => $room->status,
            'capacity'        => $room->capacity,
            'occupied_beds'   => $occupiedBeds->count(),
            'available_beds'  => $availableBeds->count(),
            'occupancy_rate'  => $room->capacity > 0
                                    ? round(($occupiedBeds->count() / $room->capacity) * 100, 1)
                                    : 0,
            'room_photo_url'  => $room->room_photo
                                    ? asset('storage/' . $room->room_photo)
                                    : null,
        ];

        if ($includeBeds) {
            $data['beds'] = $beds->map(fn ($bed) => [
                'id'          => $bed->id,
                'bed_number'  => $bed->bed_number,
                'is_occupied' => (bool) $bed->is_occupied,
            ])->values();
        }

        return $data;
    }

    // ----------------------------------------------------------------
    // GET /api/rooms
    // All rooms with occupied + available bed counts.
    // Optional query params:
    //   ?type=standard|deluxe   — filter by room type
    //   ?status=active|maintenance — filter by room status
    // ----------------------------------------------------------------
    public function index(Request $request): JsonResponse
    {
        $query = Room::with('beds');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rooms = $query->orderBy('room_number')->get();

        return response()->json([
            'status' => 'success',
            'total'  => $rooms->count(),
            'rooms'  => $rooms->map(fn ($room) => $this->roomResource($room)),
        ]);
    }

    // ----------------------------------------------------------------
    // GET /api/rooms/available
    // Only rooms that have at least 1 vacant bed AND are active.
    // Optional query param:
    //   ?type=standard|deluxe
    // ----------------------------------------------------------------
    public function available(Request $request): JsonResponse
    {
        $query = Room::with('beds')
            ->where('status', 'active')
            ->whereHas('beds', fn ($q) => $q->where('is_occupied', false));

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $rooms = $query->orderBy('room_number')->get();

        // Filter rooms whose loaded beds actually have vacancies
        // (whereHas already handles this at DB level, but we confirm
        // available_beds > 0 in the resource for accuracy)
        $rooms = $rooms->filter(fn ($room) => $room->beds->where('is_occupied', false)->count() > 0);

        return response()->json([
            'status' => 'success',
            'total'  => $rooms->count(),
            'rooms'  => $rooms->map(fn ($room) => $this->roomResource($room))->values(),
        ]);
    }

    // ----------------------------------------------------------------
    // GET /api/rooms/{id}
    // Single room detail including full bed list with occupancy status.
    // ----------------------------------------------------------------
    public function show(int $id): JsonResponse
    {
        $room = Room::with('beds')->find($id);

        if (! $room) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Room not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'room'   => $this->roomResource($room, includeBeds: true),
        ]);
    }
}
