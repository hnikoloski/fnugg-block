/**
 * WordPress components that create the necessary UI elements for the block
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-components/
 */
import { TextControl, Panel, PanelBody, PanelRow } from '@wordpress/components';
import Card from './components/card';

/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';


/**
 * The edit function describes the structure of your block in the context of the
 * editor. This represents what the editor will render when the block is used.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#edit
 *
 * @param {Object}   props               Properties passed to the function.
 * @param {Object}   props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function that updates individual attributes.
 *
 * @return {WPElement} Element to render.
 */
export default function Edit({ attributes: { resortName, resortData }, setAttributes }) {
	const blockProps = useBlockProps();

	const fetchResortData = (resort) => {
		let homeUrl = window.location.origin;
		let api_url = `${homeUrl}/wp-json/mollie/v1/search?q=${resort}`;
		fetch(api_url)
			.then((response) => response.json())
			.then((data) => {
				setAttributes({ resortData: data });
			});
	};

	const onChangeValue = (resortName) => {
		const siteUrl = window.location.origin;
		const url = `${siteUrl}/wp-json/mollie/v1/suggest?q=${resortName}`;
		const suggestions = document.getElementById('fnugg-suggestions');

		fetch(url)
			.then((response) => response.json())
			.then((data) => {
				if (resortName === '') {
					suggestions.innerHTML = '';
				} else if (data.code == 'fnugg_error') {
					suggestions.innerHTML = '<li class="err-msg">' + data.message + '</li>';
				} else {
					suggestions.innerHTML = '';
					data.forEach((item) => {
						const li = document.createElement('li');
						// Decode amp entities if any
						if (item.includes('&amp;')) {
							item = item.replace(/&amp;/g, '&');
						}
						li.innerHTML = item;
						li.addEventListener('click', () => {
							suggestions.innerHTML = '';

							setAttributes({ resortName: item });

							fetchResortData(encodeURIComponent(item));
						});
						suggestions.appendChild(li);

					});
				}
			})
		// Set resortName to the value of the input field
		setAttributes({ resortName });

	};

	return (
		<>
			<InspectorControls>
				<Panel header="Fnugg Block Settings"
					className='fnugg-panel'>
					<PanelBody title="Fnugg Card Settings">
						<div className='searchWrapper'>
							<TextControl
								placeholder='Search for a location'
								value={resortName}
								onChange={(resortName) => { onChangeValue(resortName); }}
								id='fnugg-search'
								autoComplete='off'
								label='Search for a location'
							/>
							<ul id='fnugg-suggestions'></ul>
						</div>
					</PanelBody>
				</Panel>
			</InspectorControls>

			<div {...blockProps}>
				<Card name={resortName} data={resortData} />
			</div>
		</>

	);
}
