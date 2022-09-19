# Fnugg.no Wp Block

Upload the plugins to the plugin directory. Or use the wp installer in the dashboard.

### General Use

Add the Fnugg block in the editor.
![App Screenshot](./assets/readme/Screenshot_1.png)

Search for a resort in the sidebar settings.
![App Screenshot](./assets/readme/Screenshot_2.png)
Click the one you want and.. Voilà Magic.

## API Reference

#### Autocomplete

```http
  GET /wp-json/mollie/v1/suggest
```

| Parameter | Type     | Description                 |
| :-------- | :------- | :-------------------------- |
| `q`       | `string` | Resort Name. Or part of it. |

#### Get item

```http
  GET /wp-json/mollie/v1/search
```

| Parameter | Type     | Description |
| :-------- | :------- | :---------- |
| `q`       | `string` | Resort name |
