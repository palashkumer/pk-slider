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
	let frame = null;


	useEffect(() => {
		fetch("/wp-json/custom/v1/pkslider/", { mode: "no-cors" })
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
	const uploadImage = (event) => {
		event.preventDefault();
	  
		// If the media frame already exists, reopen it.
		if (frame) {
		  frame.open();
		  return;
		}
	  
		// Create a new media frame
		frame = wp.media({
		  title: 'Select or Upload Media Of Your Chosen Persuasion',
		  button: {
			text: 'Use this media',
		  },
		  multiple: false,
		});
	  
		// Open the modal on click
		frame.open();
	  
		// Listen for media selection
		frame.on('select', function () {
		  const attachment = frame.state().get('selection').first().toJSON();
	  
		  // Update state with the selected media information
		  setSliderData((prevSliderData) => [
			...prevSliderData,
			{
			  id: attachment.id,
			  image_url: attachment.url,
			},
		  ]);
	  
		  // Close the frame after handling the media
		  frame.close();
		});
	  
		// Handle errors when opening or closing the frame
		frame.on('error', function () {
		  console.error('Error occurred with the media frame.');
		});
	  
		frame.on('close', function () {
		  // Clean up or perform any actions when the frame is closed
		});
	  };
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
			<button type="button" onClick={uploadImage}>
				Add Image 
			</button>
		</>
	);
};
