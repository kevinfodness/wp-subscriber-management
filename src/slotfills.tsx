import { usePostMetaValue } from '@alleyinteractive/block-editor-tools';
import { ToggleControl } from '@wordpress/components';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins'

function Slotfills() {
	const [skipPush, setSkipPush] = usePostMetaValue('wp_subscriber_management_skip_push');

	return (
		<PluginDocumentSettingPanel
			icon="email"
			name="wp-subscriber-management"
			title={__('Subscriber Management', 'wp-subscriber-management')}
		>
			<ToggleControl
				checked={skipPush}
				label={__('Skip Push on Publish', 'wp-subscriber-management')}
				onChange={() => setSkipPush(!skipPush)}
			/>
		</PluginDocumentSettingPanel>
	);
}

registerPlugin( 'wp-subscriber-management', { render: Slotfills } );
