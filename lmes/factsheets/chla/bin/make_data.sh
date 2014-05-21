#!/bin/bash

# author: Bruno Combal
# date: September 2013
# purpose: read original data and create well-formated data files and create html template files

inDir='../annual_mean_chl_data_csv_files'
template='chla_template.html'
outiframe='../iframes'
outdata='../data'
codeFile='../data/'

for f in ${inDir}/*.CSV
do
    lme=$(awk -F ',' 'NR==2 {print $1}' $f | tr '[:upper:]' '[:lower:]')

    


    lmeName=$(echo ${lme} | sed 's/_/ /g' | sed -e "s/\b\(.\)/\u\1/g")
    outfile=${outdata}/${lme}.csv
    awk -F ',' '{if ($3>2) print $2,$4,$5,$6,$7,$10}' $f | sed 's/!Y_//g' > ${outfile}
    # create a new html iframe
    iframe=${outiframe}/chla_${lme}.html
    cp ${template} ${iframe}
    # update information in the html page
    title=($(echo $lme | sed 's/_/\ /g'))
    echo ${title[@]}
    perl -i -pe 's/CHARTTITLETOREPLACE/Chlorophyl-a ('"${lmeName}"')/' ${iframe}
    recodedoutfile=$(echo $outfile | sed 's|/|\\/|g' | sed 's|\.|\\.|g')
    echo $recodedoutfile
    perl -pi.back -e "s|DATA\_FILE\_FULL\_PATH|${recodedoutfile}|" ${iframe}
done