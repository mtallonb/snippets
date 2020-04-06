% This code implements the articles:
% [1] M. Tallón, J. Mateos, S.D. Babacan, R. Molina, and A.K. Katsaggelos, “Space-variant
% kernel deconvolution for dual exposure problem,” in 19th European Signal Processing
% Conference (EUSIPCO), 2011, pp. 1678–1682.

% [2] M.Tallón, J. Mateos, S. D. Babacan, R. Molina, and A. K. Katsaggelos, “Space-variant
% blur deconvolution and denoising in the dual exposure problem,” Information Fusion,
% pp.–, 2012

% Copyright (C) 2012  M. Tallon, J. Mateos, S. D. Babacan, R. Molina, and A. K. Katsaggelos
%
%  If you use this software to evaluate any of the methods, please cite
%  the corresponding papers (see README.txt).

rng 'default'; %Same random values
boundary = 'replicate'; %'circular'%'symmetric'; %Boundaries behaviour for imfilter
interpolation='bicubic'; % Interpolation method
verbose=0; %Flag to display outputs
FAST=0; %Flag to run the fast deconvolution algorithm in [1] or [2].

%% ALG PARAMETER
% Seq1 DATASET:
% hSize=[31 31]; /Seq01
% numBlock=[6 8];

hSize=[21 21]; %CLK blur size
numBlock=[6 6]; %Amount of patches

overlapPerc=0.5; % Overlap percentage among patches
t=2; % Threshold for denoising selection. Higher values reduce denoising.

DIRMAT='./mat';
DIRIMG='./IMG';
DIROUTPUT='./OUTPUT';
DIRDATASET='/CLK';

%% INPUTS

% Seq1 DATASET:
% Blurred=imread([DIRIMG,DIRDATASET,'/img_1_q20000_v2_09.tiff']);
% Noisy=imread([DIRIMG,DIRDATASET,'/img_1_q20000_v2_02.tiff']);
%
% %BUG on imhist using uint16. Conversion to uint8.
% Blurred=uint8(double(Blurred)/256);
% Noisy=uint8(double(Noisy)/256);
% TamFin=[600 800];
% oversize=[100 100];
% % [img,rect]=imcrop(Blurred);
% rect=[71,50,TamFin(2)+oversize(2),TamFin(1)+oversize(1)];
% escalado=1;

Blurred=imread([DIRIMG,DIRDATASET,'/Img0071.jpg']);
Noisy=imread([DIRIMG,DIRDATASET,'/Img0068.jpg']);

TamFin=[512 512]; % Image size after calibration process
oversize=[600 600]; % Oversize for the calibration
rect=[500,320,TamFin(2)+oversize(2),TamFin(1)+oversize(1)]; % ROI
scale=1/2; % Scale for the cropping
show=0; % To show images

%% IMAGE PAIR CALIBRATION
[y1YCC y2NoisyYCC y2YCC]=calibrate(Blurred,Noisy,TamFin,rect,scale,show);

y1B=y1YCC(:,:,1);
y2NoisyB=y2NoisyYCC(:,:,1);

%% BLOCKS DIVISION
padding=(hSize-1)/2;
xSize=size(y1B)-padding*2;

blockSize=floor(xSize./((numBlock).*(1-overlapPerc)+overlapPerc));
overlap=floor(blockSize*overlapPerc);
[blockSize, overlap]=correctBlockSize(numBlock, blockSize, overlap, xSize,overlapPerc);
nonOV=blockSize-overlap;

xSize=(numBlock-1).*(blockSize-overlap)+blockSize;
y1B=y1B(1:xSize(1)+2*padding,1:xSize(2)+2*padding);
y2NoisyB=y2NoisyB(1:xSize(1)+2*padding,1:xSize(2)+2*padding);

bloquesY1=recorta(y1B, numBlock, blockSize, overlap ,padding);
bloquesY2=recorta(y2NoisyB, numBlock, blockSize, overlap ,padding);

%% KERNEL ESTIMATION
tic
bloques_hEst = cellfun(@(bloquesY1,bloquesY2) kernelEstimationV3(bloquesY1, bloquesY2, hSize),bloquesY1,bloquesY2, 'UniformOutput', false);
Ktime=toc

if FAST
    thrKCorrection=0.5;
    tic
    [ bloques_hCor, correctedMatrix ] = KCorrection2011( bloques_hEst,thrKCorrection );
    KCorrtime=toc
end

save([DIROUTPUT,DIRDATASET]);


%% DECONVOLUTION

if FAST
    initVar1=0.001;
    initVar2=var(y1B(:)-y2NoisyB(:));
    
    tic
    [bloquesRest,var1Est,var2Est] = cellfun(@(bloquesY1,bloques_hEst,bloquesY2, bloquesX)...
        deconvEusipco2011(bloquesY1,bloques_hEst,bloquesY2,bloquesY1,initVar1,initVar2)...
        ,bloquesY1,bloques_hCor,bloquesY2,bloquesY1,'UniformOutput', false);
    Tdeconv=toc
    
    rectBlock=[1+padding(2),1+padding(1),blockSize(2)-1,blockSize(1)-1];
    bloquesRest= cellfun(@(bloquesRest) imcrop(bloquesRest,rectBlock),...
        bloquesRest, 'UniformOutput', false);
    TdeconvFast=toc
    
else
    lambdas=1:-0.1:0.5;
    
    restauraciones_L=cell(numBlock(1),numBlock(2),size(lambdas'));
    
    varianzas1_L=zeros(size(restauraciones_L));
    varianzas2_L=varianzas1_L;
    
    tic
    for ind=1:length(lambdas)
        lambda=lambdas(ind);
        disp([' lambda= ' num2str(lambda)]);
        [temp_resBlocks,temp_var1Est,temp_var2Est] = cellfun(@(bloquesY1,bloques_hEst,bloquesY2)...
            DeconvCG_TV_SAR(bloquesY1,bloques_hEst,bloquesY2,bloquesY1,[],[],lambda,verbose)...
            ,bloquesY1,bloques_hEst,bloquesY2, 'UniformOutput', false);
        
        restauraciones_L(:,:,ind)= temp_resBlocks;
        varianzas1_L(:,:,ind)=cell2mat(temp_var1Est);
        varianzas2_L(:,:,ind)=cell2mat(temp_var2Est);
    end
    Tdeconv=toc
end
save([DIROUTPUT,DIRDATASET]);

%% LAMBDA SELECTION
rectBlock=[1+padding(2),1+padding(1),blockSize(2)-1,blockSize(1)-1];
bloquesY1C= cellfun(@(bloquesY1) imcrop(bloquesY1,rectBlock), bloquesY1, 'UniformOutput', false);
bloquesY2C= cellfun(@(bloquesY2) imcrop(bloquesY2,rectBlock), bloquesY2, 'UniformOutput', false);

if ~FAST
    
    Errorsy1Hx=cell(size(restauraciones_L));
    Errorsy1x=Errorsy1Hx;
    Errorsy2x=Errorsy1Hx;
    
    for ind=1:size(lambdas')
        temp=restauraciones_L(:,:,ind);
        temp= cellfun(@(temp) imcrop(temp,rectBlock), temp, 'UniformOutput', false);
        
        [~, Errory1Hx, Errory1x, Errory2x]=cellfun(@(bloquesY1C,bloquesY2C,temp,bloques_hEst)...
            errores(bloquesY1C,bloquesY2C, temp, bloques_hEst)...
            , bloquesY1C,bloquesY2C,temp,bloques_hEst,'UniformOutput', false);
        
        Errorsy1Hx(:,:,ind)=Errory1Hx;
        Errorsy1x(:,:,ind)=Errory1x;
        Errorsy2x(:,:,ind)=Errory2x;
        
    end
    
    Errorsy1Hx=cell2mat(Errorsy1Hx);
    Errorsy1x=cell2mat(Errorsy1x);
    Errorsy2x=cell2mat(Errorsy2x);
    
    Errorsy1Hx=normalize01(Errorsy1Hx);
    Errorsy1x=normalize01(Errorsy1x);
    Errorsy2x=normalize01(Errorsy2x);
    
    %  Strategies for selection
    [MinE,MinE_ind]=min(Errorsy1Hx+Errorsy2x-Errorsy1x,[],3);
    
    restauraciones_Best=cell(numBlock);
    
    LambdasBest=zeros(size(restauraciones_Best));
    
    Vars_n2Best=LambdasBest;
    
    for f=1:numBlock(1)
        for c=1:numBlock(2)
            
            restauraciones_Best(f,c)=restauraciones_L(f,c,MinE_ind(f,c));
            LambdasBest(f,c)=lambdas(MinE_ind(f,c));
            Vars_n2Best(f,c)=varianzas2_L(f,c,MinE_ind(f,c));
            
        end
    end
    
    restauraciones_Best= cellfun(@(restauraciones_Best) imcrop(restauraciones_Best,rectBlock),...
        restauraciones_Best, 'UniformOutput', false);
end

%% DENOISING SELECTION
rectF=[1+padding(2),1+padding(1),xSize(2)-1,xSize(1)-1];
y1=imcrop(y1B,rectF);
y2Noisy=imcrop(y2NoisyB,rectF);

if FAST
    var2Est=cell2mat(var2Est);
    var2Media=mean(var2Est(:));
    
    tic
    [~, deNoised] = BM3D(1, uint8(y2NoisyB),sqrt(var2Media));
    deNoised=deNoised*255;
    Tdenoising=toc
    
    bloquesDN=recorta(deNoised, numBlock, blockSize, overlap ,padding);
    bloquesDN= cellfun(@(bloquesDN) imcrop(bloquesDN,rectBlock), bloquesDN, 'UniformOutput', false);
    
    [DNrestauracionesBest,PatchesNoiseBest] = cellfun(@(bloquesY1C,bloquesY2C,bloques_hCor,restauraciones_Best, bloquesDN)...
        dnSelectionErrors(bloquesY1C,bloquesY2C,bloques_hCor,restauraciones_Best, bloquesDN)...
        ,bloquesY1C,bloquesY2C,bloques_hCor,bloquesRest, bloquesDN,'UniformOutput', false);
    PatchesNoiseFull=pega(PatchesNoiseBest,[0 0]);
    deNoised=imcrop(deNoised,rectF);
else
    tic
    [DNrestauracionesBest,PatchesNoiseFull,Var2Best,deNoised]=...
        dnSelection(Vars_n2Best,t,y2Noisy,numBlock,blockSize,overlap, restauraciones_Best);
    Tdenoising=toc
end

%% OUTPUTS

% blending between windows is done via the following windows
ws = create_windows(@barthannwin, numBlock, blockSize, nonOV);

hFullEst=pega(bloques_hEst,[0 0]);

DNrestauracionesW_Best = cellfun(@times, DNrestauracionesBest, ws, 'UniformOutput', false);
Xdn_Best=pega(DNrestauracionesW_Best,overlap);

y1YCC_C=imcrop(y1YCC,rectF);
y2YCC_C=imcrop(y2YCC,rectF);
y2NoisyYCC_C=imcrop(y2NoisyYCC,rectF);

xColorFinal=y1YCC_C;
xColorDenoised=y1YCC_C;

xColorFinal(:,:,1)= Xdn_Best;
xColorDenoised(:,:,1)=deNoised;

xColorFinal=uint16(xColorFinal).*256;
xColorRGBFinal=ycbcr2rgb(uint16(xColorFinal));

xColorRGBDenoised=ycbcr2rgb(uint8(xColorDenoised));

imwrite(ycbcr2rgb(uint16(y1YCC_C).*256),strcat(DIROUTPUT,DIRDATASET,'y1','.tiff'),'tiff');
imwrite(ycbcr2rgb(uint16(y2YCC_C).*256),strcat(DIROUTPUT,DIRDATASET,'y2','.tiff'),'tiff');
imwrite(ycbcr2rgb(uint16(y2NoisyYCC_C).*256),strcat(DIROUTPUT,DIRDATASET,'y2Noisy','.tiff'),'tiff');
imwrite(xColorRGBFinal,strcat(DIROUTPUT,DIRDATASET,'xdeBlurredCorrected','.tiff'),'tiff');

imwrite(xColorRGBFinal,strcat(DIROUTPUT,DIRDATASET,'xdeBlurredCorrected','.tiff'),'tiff');
imwrite(xColorRGBDenoised,strcat(DIROUTPUT,DIRDATASET,'xDenoised','.png'),'png');

imwrite(uint8((255/max(hFullEst(:)).*hFullEst)),strcat(DIROUTPUT,DIRDATASET,'hEst','.png'),'png');
imwrite(uint8((255/max(PatchesNoiseFull(:)).*PatchesNoiseFull)),strcat(DIROUTPUT,DIRDATASET,'PatchesNoiseBest','.png'),'png');

save([DIROUTPUT,DIRDATASET]);
