<?php

include_once __DIR__ . '/../serializers/PermissionSerializer.php';
include_once __DIR__ . '/../../common/generic_views/abstract.php';
include_once __DIR__ . '/../../common/generic_views/response/JSONResponse.php';


class UpdateUserView extends UpdateAPIView {

	protected function getCommandKey(): string {
		global $CMD_UPDATE_USER;
		return $CMD_UPDATE_USER;
	}

	protected function getSerializer() {
		return UserSerializer::class;
	}

	public function getRequestData( $request ): array {
		$data = parent::getRequestData( $request );
		$data['id'] = $this->urlArgs['id'];
		return $data;
	}
}

class ListUsersView extends ListAPIView {

	protected function getCommandKey(): string {
		global $CMD_LISTS_USERS;

		return $CMD_LISTS_USERS;
	}

	protected function getSerializer() {
		return UserSerializer::class;
	}
}

class ListUserPermissionsView extends ListAPIView {

	protected function getCommandKey(): string {
		global $CMD_LIST_USER_PERMISSIONS;
		return $CMD_LIST_USER_PERMISSIONS;
	}

	protected function getSerializer() {
		return PermissionSerializer::class;
	}

	public function getExecutionContext( $request ): ExecutionContext {
		$ctx = parent::getExecutionContext($request);
		$ctx->setValue('forUser', $this->urlArgs['uid']);
		return $ctx;
	}
}

class TogglePermissionsView extends AbstractAPIView {

	public function getExecutionContext( $request ): ExecutionContext {
		$ctx = parent::getExecutionContext( $request );
		$ctx->setValue('forUser', $this->urlArgs['uid']);
		$ctx->setValue('permissionId', $this->urlArgs['id']);
		$ctx->setValue('toggle', $request->postData['toggle']);
		return $ctx;
	}

	public function post(Request $request): AbstractResponse {

		global $CMD_TOGGLE_USER_PERMISSION;
		$ctx = $this->getExecutionContext($request);
		$result = Executor::getInstance()->execute($CMD_TOGGLE_USER_PERMISSION, $ctx);

		if ($result->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		}

		return new JSONResponse(200, null);
	}
}


class UserDevicesListView extends ListAPIView {

	protected function getCommandKey(): string {
		global $CMD_LIST_USER_DEVICES;
		return $CMD_LIST_USER_DEVICES;
	}

	protected function getSerializer() {
		return DeviceSerializer::class;
	}
}


class AddDeviceView extends AbstractAPIView {

	public function getExecutionContext( $request ): ExecutionContext {
		$ctx = parent::getExecutionContext( $request );
		$ctx->setValue('forUser', $this->urlArgs['uid']);
		$ctx->setValue('deviceId', $request->postData['deviceId']);
		return $ctx;
	}

	public function post(Request $request): AbstractResponse {

		global $CMD_ADD_DEVICE;
		$ctx = $this->getExecutionContext($request);
		$result = Executor::getInstance()->execute($CMD_ADD_DEVICE, $ctx);

		if ($result->getResultMeta()->getState() == ResultState::INSUFFICIENT_PERMISSIONS) {
			return new UnauthorizedResponse();
		} else if ($result->getResultMeta()->getState() == ResultState::OPERATION_ERROR) {
			return new JSONResponse(403, array('error' => $result->getResultMeta()->getMessage()));
		}

		return new JSONResponse(200, null);
	}
}
