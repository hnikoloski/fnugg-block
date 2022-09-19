const Card = ({ data }) => {
    if (!data || data.length === 0) {
        return <>
            <h2>
                Search for a location..
            </h2>
        </>;
    }
    let name = data.name
    // decode amp entities if any
    if (name.includes('&amp;')) {
        name = name.replace(/&amp;/g, '&');
    }

    let last_updated = data.last_updated
    let condition = data.condition
    let conditionIcon = data.conditionIcon
    let temperature = data.temperature.value
    let image = data.image
    let windSpeed = data.wind.mps
    let windDegree = data.wind.degree
    let slopesOpen = data.slopes

    let conditionIcons = [
        'clear-day',
        'brightness_4',
        'cloudy',
        'weather_snowy',
        'ac_unit',
        'water_drop',
        'rainy',
        'severe_cold',
        'sunny_snowing'
    ]

    conditionIcon = conditionIcons[conditionIcon];


    return (
        <div id='fnugg-card' className='fnugg-card'>
            <div className="fnugg-card__header">
                <p className='name'>{name}</p>
                <img src={image} alt={name} />
                <div className="fnugg-card__header__info">
                    <p>today's situation</p>
                    <p>Last updated: {last_updated}</p>
                </div>
            </div>
            <div className="fnugg-card__body">
                <div className="fnugg-card__body__info weather">
                    <span class="material-symbols-outlined icon">
                        {conditionIcon}
                    </span>
                    <p>{condition}</p>
                </div>
                <div className="fnugg-card__body__info temperature">
                    <p>{temperature}</p>
                </div>
                <div className="fnugg-card__body__info wind">
                    <span class="material-symbols-outlined icon" style={
                        {
                            transform: `rotate(${windDegree - 45}deg)`, // -45 because the icon is rotated 45deg by default
                        }
                    }>
                        near_me
                    </span>
                    <p>{windSpeed} <span>m/s</span></p>
                </div>
                <div className="fnugg-card__body__info slopes">
                    <span class="material-symbols-outlined icon">
                        downhill_skiing
                    </span>
                    <p>Slopes Open: {slopesOpen}</p>
                </div>

            </div>
        </div >

    )
}



export default Card