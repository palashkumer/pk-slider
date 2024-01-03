import React, { useState, useEffect } from "react";
import "swiper/css";
import "swiper/css/navigation";

// Import Swiper React components
import { Swiper, SwiperSlide } from "swiper/react";
import { Navigation } from "swiper/modules";

export const SwiperSlider = () => {
	const [sliderData, setSliderData] = useState([]);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
  
    useEffect(() => {
      fetch("/wp-json/custom/v1/pkslider/",{ mode: 'no-cors' })
        .then((response) => {
          if (!response.ok) {
            throw new Error("Failed to fetch data");
          }
          return response.json();
        })
        .then((data) => {
          setSliderData(data);
          setLoading(false);
        })
        .catch((error) => {
          setError(error.message);
          setLoading(false);
        });
    }, []);
  
    if (loading) {
      return <p>Loading...</p>;
    }
  
    if (error) {
      return <p>Error: {error}</p>;
    }
    console.log(sliderData);
	return (
		<>
			<Swiper navigation={true} modules={[Navigation]} className="mySwiper">
				{sliderData.map((item, index) => (
					<SwiperSlide key={item.ID}>
                       <img src={item?.image_url} alt={`Slide ${index}`} />
                    </SwiperSlide>
				))}
			</Swiper>
		</>
	);
};
