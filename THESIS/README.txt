-------------------------------------------------------------------

  Demo software for dual image restoration and enhancement  
                   Public release v1.0 (26 November 2012) 

-------------------------------------------------------------------

Copyright (c) 2012 Universidad de Granada. 
All rights reserved.
This work should be used for nonprofit purposes only.

Authors:                     Miguel Tallón
                             Javier Mateos
                             Derin Babacan
							 Rafael Molina
							 Aggelos Katsaggelos


web page:               http://www. PONER LA WEB DEL PROYECTO


-------------------------------------------------------------------
 Contents
-------------------------------------------------------------------

The package comprises these functions

*) SpatiallyVariantDeblur.m	: Run the demo with the CLK dataset
*) calibrate				: Calibrates geometrically and photometrically the image pair
*) deconvEusipco2011		: Implements the fast deconvolution method on [1]
*) DeconvCG_TV_SAR    		: Implements the deconvolution based on image prior model combination [2]
*) dnSelectionError   		: Allows to select the deconvolved patch or the denoised using the errors measures detailed on [1]
*) dnSelection   			: Allows to select the deconvolved patch or the denoised
*) kernelEstimationV3	 	: Estimate the kernel on each patch
*) KCorrection2011		 	: Kernel correction stage whose detail can be found on [1]
*) miConvmtxV2   			: Compute the convolution matrix in order to perform the convolution as a product of matrixes.


For help on how to use these scripts, you can e.g. use "help BM3D".

-------------------------------------------------------------------
 Installation
-------------------------------------------------------------------

Unzip the archive in a folder that is in the MATLAB path.


-------------------------------------------------------------------
 Requirements
-------------------------------------------------------------------

*) MS Windows (32 or 64 bit), Linux (32 bit or 64 bit)
   or Mac OS X (32 or 64 bit)
*) Matlab v.7.1 or later with installed:
   -- Image Processing Toolbox (for visualization with "imshow")


-------------------------------------------------------------------
 Change log
-------------------------------------------------------------------



-------------------------------------------------------------------
 References
-------------------------------------------------------------------

[1] M. Tallón, J. Mateos, S.D. Babacan, R. Molina, and A.K. Katsaggelos, “Space-variant
kernel deconvolution for dual exposure problem,” in 19th European Signal Processing
Conference (EUSIPCO), 2011, pp. 1678–1682.

[2]  M.Tallón, J. Mateos, S. D. Babacan, R. Molina, and A. K. Katsaggelos, “Space-variant
 blur deconvolution and denoising in the dual exposure problem,” Information Fusion, pp.–, 2013

 
-------------------------------------------------------------------
 Disclaimer
-------------------------------------------------------------------

Any unauthorized use of these routines for industrial or profit-
oriented activities is expressively prohibited.


-------------------------------------------------------------------
 Feedback
-------------------------------------------------------------------

If you have any comment, suggestion, or question, please do
contact  Miguel Tallón  at  mtallon@decsai.ugr.es

